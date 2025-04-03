<?php

namespace App\Controllers;

class SocialAuthController extends BaseController
{
    private $app_id = '1029305132424021';
    private $app_secret = '452a2ac02640720d45b60c2d48e56902';

    public function facebook()
    {
        $redirect_uri = urlencode(base_url('social/facebook/callback'));
        $scope = 'email,public_profile,pages_show_list,pages_read_engagement,pages_manage_posts,instagram_basic,instagram_content_publish';

        $login_url = "https://www.facebook.com/v17.0/dialog/oauth?"
            . "client_id={$this->app_id}&redirect_uri={$redirect_uri}&scope={$scope}&response_type=code";

        return redirect()->to($login_url);
    }

    public function facebookCallback()
    {
        $code = $this->request->getGet('code');
        $redirect_uri = base_url('social/facebook/callback');

        $token_url = "https://graph.facebook.com/v17.0/oauth/access_token?"
            . "client_id={$this->app_id}&redirect_uri=" . urlencode($redirect_uri)
            . "&client_secret={$this->app_secret}&code={$code}";

        $response = file_get_contents($token_url);
        $data = json_decode($response, true);

        if (!isset($data['access_token'])) {
            echo "Failed to get access token.";
            return;
        }

        $access_token = $data['access_token'];

        // Step 1: Get managed Pages
        $pages_url = "https://graph.facebook.com/v17.0/me/accounts?access_token={$access_token}";
        $pages = json_decode(file_get_contents($pages_url), true);

        // Step 2: Store to DB (replace with your logic)
        foreach ($pages['data'] as $page) {
            $page_id = $page['id'];
            $page_token = $page['access_token'];
            $page_name = $page['name'];

            // Get IG account linked to FB page
            $ig_response = json_decode(file_get_contents("https://graph.facebook.com/v17.0/{$page_id}?fields=instagram_business_account&access_token={$page_token}"), true);
            $instagram_id = $ig_response['instagram_business_account']['id'] ?? null;

            // Store in DB — use your Crud logic here
            $this->Crud->create('social_connect', [
                'type' => 'member',
                'type_id' => session()->get('td_id')
            ], [
                'facebook' => json_encode([
                    'access_token' => $page_token,
                    'page_id' => $page_id,
                    'page_name' => $page_name
                ]),
                'instagram' => json_encode([
                    'instagram_id' => $instagram_id,
                    'page_id' => $page_id
                ]),
                'facebook_perm' => 1,
                'instagram_perm' => $instagram_id ? 1 : 0,
                'updated_date' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to('/dashboard')->with('success', 'Facebook connected successfully!');
    }
}

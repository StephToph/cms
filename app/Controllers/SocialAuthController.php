<?php

namespace App\Controllers;

class SocialAuthController extends BaseController
{
    private $app_id = '585237394533457';
    private $app_secret = 'a89301a7b5495b7971f982c871fd6ca2';

    public function facebook()
    {
        $redirect_uri = urlencode(base_url('social/facebook/callback'));
        $scope = 'email,public_profile,pages_show_list,pages_read_engagement,pages_manage_posts,instagram_basic,instagram_content_publish';

        $login_url = "https://www.facebook.com/v17.0/dialog/oauth?"
            . "client_id={$this->app_id}&redirect_uri={$redirect_uri}&scope={$scope}&response_type=code";

        return redirect()->to($login_url);
    }
    public function facebookCallback(){
        $code = $this->request->getGet('code');
        $redirect_uri = base_url('social/facebook/callback');
    
        $token_url = "https://graph.facebook.com/v17.0/oauth/access_token?"
            . "client_id={$this->app_id}&redirect_uri=" . urlencode($redirect_uri)
            . "&client_secret={$this->app_secret}&code={$code}";
    
        $response = file_get_contents($token_url);
        $data = json_decode($response, true);
    
        if (!isset($data['access_token'])) {
            log_message('error', 'Access token not found from Facebook callback');
            echo "Access token not received."; return;
        }
    
        $access_token = $data['access_token'];
    
        // Step 1: Fetch Pages
        $pages_url = "https://graph.facebook.com/v17.0/me/accounts?access_token={$access_token}";
        $pages_response = file_get_contents($pages_url);
        $pages = json_decode($pages_response, true);
    
        if (empty($pages['data'])) {
            return view('social/no_pages_found', [
                'login_url' => base_url('social/facebook'),
                'create_page_url' => 'https://www.facebook.com/pages/create'
            ]);
        }
        
    
        // Step 2: Get Session User Info
        $type = 'church'; // or 'user'
        $type_id = session()->get('church_id'); // make sure this is set in session
    
        if (!$type_id) {
            log_message('error', 'Missing type_id from session.');
            echo "Session missing church ID."; return;
        }
    
        foreach ($pages['data'] as $page) {
            $page_id = $page['id'];
            $page_token = $page['access_token'];
            $page_name = $page['name'];
    
            // Get Instagram account if any
            $ig_data = file_get_contents("https://graph.facebook.com/v17.0/{$page_id}?fields=instagram_business_account&access_token={$page_token}");
            $ig_info = json_decode($ig_data, true);
            $instagram_id = $ig_info['instagram_business_account']['id'] ?? null;
    
            $facebook_data = json_encode([
                'access_token' => $page_token,
                'page_id' => $page_id,
                'page_name' => $page_name,
            ]);
    
            $instagram_data = json_encode([
                'instagram_id' => $instagram_id,
                'page_id' => $page_id,
            ]);
    
            // Log before DB save
            log_message('info', "Saving social connection for: type=$type, id=$type_id");
    
            // Save to DB
            $this->Crud->create_or_update('social_connect', [
                'type' => $type,
                'type_id' => $type_id,
            ], [
                'facebook' => $facebook_data,
                'instagram' => $instagram_data,
                'facebook_perm' => 1,
                'instagram_perm' => $instagram_id ? 1 : 0,
                'updated_date' => date('Y-m-d H:i:s')
            ]);
        }
    
        return redirect()->to('/dashboard')->with('success', 'Facebook connected successfully!');
    }

}

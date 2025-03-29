<?php
use App\Models\Crud;
$this->Crud = new Crud();

$this->session = \Config\Services::session();


$username = $this->Crud->read_field('id', $log_id, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $log_id, 'user', 'surname');
$log_name = $this->Crud->read_field('id', $log_id, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $log_id, 'user', 'surname');
$email = $this->Crud->read_field('id', $log_id, 'user', 'email');
$log_user_img = $this->Crud->read_field('id', $log_id, 'user', 'img_id');
$log_role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

$log_role = strtolower($this->Crud->read_field('id', $log_role_id, 'access_role', 'name'));
if (empty($log_user_img) && !file_exists($log_user_img)) {
    $log_user_img = 'assets/images/avatar.png';
}
$logo = 'assets/new_logo1.png';
$min_title = $title;

$active = $this->Crud->read_field2('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'status');
if($active > 0){
    return redirect()->to(site_url('attendance/logout'));	
}


header("Access-Control-Allow-Origin: *");  // Replace * with the specific origin(s) you want to allow
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../">
    <meta charset="utf-8">
    <meta name="author" content="Angel Church Management System">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Church Performnce Tracking">
    <meta name="theme-color" content="blue">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?= site_url($logo); ?>">
    <title><?= $min_title; ?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?= site_url(); ?>assets/css/dashlitee5ca.css?ver=3.2.3">
    <link id="skin-default" rel="stylesheet" href="<?= site_url(); ?>assets/css/skins/theme-egyptian.css?ver=3.2.3">
</head>


<body class="nk-body npc-invest bg-lighter ">
    <style>
        td,
        th {
            white-space: nowrap;
            /* Prevent text from wrapping to the next line */
            overflow: hidden;
            /* Hide any overflow content */
            text-overflow: ellipsis;
            /* Display ellipsis (...) for overflowed content */
        }

        /* Styles for chat icon */
        #chat-icon {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
            cursor: pointer;
        }

        /* Chat window custom styles */
        #chat-window {
            display: none;
            bottom: 80px;
            right: 20px;
            width: 300px;
            max-height: 400px;
        }

        /* Message bubbles */
        .bot-message {
            background-color: #f1f1f1;
            padding: 8px;
            border-radius: 5px;
            margin: 5px 0;
        }

        .user-message {
            background-color: #007bff;
            color: white;
            padding: 8px;
            border-radius: 5px;
            margin: 5px 0;
            text-align: right;
        }
    </style>
    <div class="nk-app-root">
        <div class="nk-wrap ">
            <div class="nk-header nk-header-fluid is-theme  is-regular">
                <div class="container-xl wide-xl">
                    <div class="nk-header-wrap">
                        <div class="nk-header-brand">
                            <a href="<?= site_url('attendance'); ?>" class="logo-link">
                                <img class="logo-light logo-img logo-img-lg" style="max-height:50px"
                                    src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?>" alt="logo">
                                <img class="logo-dark logo-img logo-img-lg" style="max-height:50px"
                                    src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?>" alt="logo-dark">
                            </a>
                        </div>
                        <div class="nk-header-menu" data-content="headerNav">
                            <div class="nk-header-mobile">
                                <div class="nk-header-brand">
                                    <a href="<?= site_url('attendance'); ?>" class="logo-link">
                                        <img class="logo-light logo-img logo-img-lg" style="max-height:50px"
                                            src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?>" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" style="max-height:50px"
                                            src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?>" alt="logo-dark">
                                    </a>
                                </div>
                               
                            </div>
                           
                        </div>
                        <div class="nk-header-tools">
                            <ul class="nk-quick-nav">
                                
                                <li class="dropdown user-dropdown order-sm-first"><a href="#" class="dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <div class="user-toggle">
                                            <div class="user-avatar sm"><em class="icon ni ni-user-alt"></em></div>
                                            <div class="user-info d-none d-xl-block">
                                                <div class="user-status"><?=ucwords($attend_type); ?></div>
                                                <div class="user-name dropdown-indicator"><?=ucwords($username); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                    <div
                                        class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1 is-light">
                                        <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                            <div class="user-card">
                                                <div class="user-avatar"><em class="icon ni ni-user-alt"></em></div>
                                                <div class="user-info"><span class="lead-text"><?=ucwords($username); ?></span><span class="sub-text"><?=$email; ?></span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a href="<?=site_url('attend_logout'); ?>"><em class="icon ni ni-signout"></em><span>Sign
                                                            out</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?=$this->renderSection('content');?>
            
            <div class="nk-footer nk-footer-fluid bg-lighter">
                <div class="container-xl wide-lg">
                    <div class="nk-footer-wrap">
                        <div class="nk-footer-copyright">&copy;<?= date('Y'); ?> <?= app_name; ?>.
                            <?= translate_phrase('All Rights Reserved.'); ?>
                        </div>
                        <div class="nk-footer-links">
                            <ul class="nav nav-sm">
                                <li class="nav-item dropup">
                                    <a class="dropdown-toggle dropdown-indicator has-indicator nav-link"
                                        data-bs-toggle="dropdown"
                                        data-offset="0,10"><small><?= $current_language; ?></small></a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                        <ul class="language-list">
                                            <?php
                                            $lang = $this->Crud->read_single_order('status', 1, 'language_code', 'name', 'asc');
                                            if (!empty($lang)) {
                                                foreach ($lang as $l) {
                                                    $l_name = $l->name;
                                                    if ($l->name == 'Hausa' || $l->name == 'Igbo' || $l->name == 'Yoruba')
                                                        $l_name = 'Nigerian';

                                                    ?>
                                                    <li>
                                                        <a href="javascript:;" onclick="lang_session(<?= $l->id; ?>)"
                                                            class="language-item">
                                                            <img src="<?= site_url(); ?>assets/images/flags/<?= strtolower($l_name); ?>.png"
                                                                alt="" class="language-flag">
                                                            <span class="language-name"><?= $l->name; ?></span>
                                                        </a>
                                                    </li>

                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-center fade" tabindex="-1" id="modal" role="dialog" data-keyboard="false"
        data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="javascript:;" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                <div class="modal-header">
                    <h6 class="modal-title"></h6>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <script src="<?= site_url(); ?>assets/js/bundle.js"></script>
    <script src="<?= site_url(); ?>assets/js/scriptse5ca.js?v=<?= time(); ?>"></script>
    
    <script src="<?=site_url(''); ?>assets/js/jsmodal.js?v=<?=time(); ?>"></script>
    <script>

        var site_url = '<?= site_url(); ?>';
        function lang_session(lang_id) {
            if (lang_id !== '') {
                $.ajax({
                    url: site_url + 'auth/language/' + lang_id,
                    success: function (data) {
                        $('#bb_ajax_msgs').html(data);
                    }
                });
            }
        }
        // Toggle chat visibility
        // Toggle chat visibility
        function toggleChat() {
            const chatWindow = document.getElementById('chat-window');
            chatWindow.style.display = chatWindow.style.display === 'none' || chatWindow.style.display === '' ? 'block' : 'none';
        }

        // Send a message and respond intelligently to basic conversations
        function sendMessage() {
            const input = document.getElementById('user-input');
            const message = input.value.trim().toLowerCase(); // Convert input to lowercase for easier matching

            if (message !== '') {
                const chatMessages = document.getElementById('chat-messages');

                // Display the user's message
                const userMessage = document.createElement('p');
                userMessage.className = 'user-message bg-primary text-white p-2 rounded';
                userMessage.textContent = input.value; // Display original case-sensitive input
                chatMessages.appendChild(userMessage);

                // Scroll to the bottom of the chat
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Clear input
                input.value = '';

                // Define response rules for basic conversations
                let botResponse = 'Coming soon'; // Default response

                // Basic conversation patterns
                const greetings = ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'Hi Angel', 'hi angel', 'Hello Angel', 'hello angel', 'hey there'];
                const howAreYou = ['how are you', 'how are you doing', 'how’s it going'];
                const timeRelated = ['what time is it', 'current time', 'tell me the time'];

                // Respond to greetings
                if (greetings.includes(message)) {
                    botResponse = 'Greetings, How can i help you today?';
                }
                // Respond to "how are you" questions
                else if (howAreYou.some(phrase => message.includes(phrase))) {
                    botResponse = 'I’m doing great, thank you! How can I assist you today?';
                }
                // Respond to time-related queries
                else if (timeRelated.some(phrase => message.includes(phrase))) {
                    const currentTime = new Date().toLocaleTimeString();
                    botResponse = `The current time is ${currentTime}.`;
                }
                // Respond to general "thank you"
                else if (message.includes('thank you') || message.includes('thanks')) {
                    botResponse = 'You’re welcome! Let me know if you need anything else.';
                }
                // Respond to goodbye messages
                else if (message.includes('bye') || message.includes('goodbye')) {
                    botResponse = 'Goodbye! Have a great day!';
                }
                // Catch-all response
                else {
                    botResponse = 'Coming soon! I’m still learning, so stay tuned for more features!';
                }

                // Respond after a short delay
                setTimeout(() => {
                    const botMessage = document.createElement('p');
                    botMessage.className = 'bot-message bg-light p-2 rounded';
                    botMessage.textContent = botResponse;
                    chatMessages.appendChild(botMessage);

                    // Scroll to the bottom of the chat
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 1000);
            }
        }

    </script>
    <?= $this->renderSection('scripts'); ?>
    
    <script src="<?php echo site_url(); ?>assets/js/libs/editors/summernote.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/editors.js"></script>
    
    <div class="js-preloader">
        <div class="loading-animation tri-ring"></div>
    </div>

</html>
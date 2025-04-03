<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>No Facebook Page Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card shadow-sm p-5">
                    <h3 class="text-danger mb-3">No Facebook Pages Found</h3>
                    <p class="mb-4">It looks like your Facebook account is not connected to any Page.<br>
                    Facebook only allows apps to post to Pages, not personal timelines.</p>

                    <div class="d-grid gap-3 col-md-6 mx-auto">
                        <a href="<?= $create_page_url ?>" class="btn btn-outline-primary" target="_blank">
                            <em class="icon ni ni-plus"></em> Create a Facebook Page
                        </a>

                        <a href="<?= $login_url ?>" class="btn btn-success">
                            <em class="icon ni ni-facebook-f"></em> Try Connecting Again
                        </a>
                    </div>

                    <div class="mt-4 text-muted small">
                        Need help? Contact support at <a href="mailto:support@yourdomain.com">support@yourdomain.com</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

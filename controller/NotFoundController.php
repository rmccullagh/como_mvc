<?php
use \Uri;
class NotFoundController extends BaseController {

    public function __construct() {

        parent::__construct();	

    }
    public function index() {

        if(!headers_sent()) {
            
            header("HTTP/1.0 404 Not Found");

            $this->view->set(array(
                "title" => "404 - Page Not Found", 
                "url" => Uri::currentUrl()
            ))->renderAsString('<div class="not-found not-found-title">The request url: "<?php echo $url; ?>" was not found.</div>');
        }

        exit();

    }
}

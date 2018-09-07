<?php
/*
 * This class renders the critical error page
 */

namespace PNM;

class CriticalError
{

    public static function show(\Throwable $e)
    {
        http_response_code(500);
        (new \PNM\views\HeadView())->render(\PNM\views\HeadView::HEADERSLIM, 'Error');
        ?><p><?php
            echo get_class($e), ' ', $e->getCode(), ': ', $e->getMessage();
            ?>
        </p>
        <?php
        require 'views/footer.php';
        exit();
    }
}

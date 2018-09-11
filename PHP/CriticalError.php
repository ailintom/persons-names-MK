<?php
/*
 * This class renders the critical error page
 */

namespace PNM;

use \PNM\views\HeadView;

class CriticalError
{

    public static function show(\Throwable $e)
    {
        http_response_code(500);
        (new HeadView())->render(HeadView::HEADERSLIM, 'Error');
        ?><p><?php
            echo get_class($e), ' ', $e->getCode(), ': ', $e->getMessage();
            ?>
        </p>
        <?php
        require 'views/footer.php';
        exit();
    }
}

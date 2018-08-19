<?php
/*
 * This class renders the critical error page
 */

namespace PNM;

class CriticalError
{

    public static function show(\Exception $e)
    {
        http_response_code(500);
        (new HeadView())->render(HeadView::HEADERSLIM, 'Error');
        ?><p>Error: <?php
            echo(get_class($e)) . '<br>';
            print_r($e);
            ?>
        </p></body></html>
        <?php
        exit();
    }
}

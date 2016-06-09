<?php
/*
 * Controlador del formulario de busqueda del panel de administración.
 */

require 'sn-admin.php';

if (SN_Users::checkRol('editor')) {
    if (filter_input(INPUT_POST, 'search')) {
        /*
         * cat: post: comm: page: user: term:
         */
        $str = filter_input(INPUT_POST, 'search');
        $explode = explode(':', $str);
        if (strpos($str, ':') === FALSE) {
            echo '<div id="header" class="clearfix">
                    <br/>
                    <h1>Resultado de la busqueda:</h1>
                </div>
                <div id="dataSearch">
                <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="contentPost">
                                <p>Datos de busqueda incorrectos.</p>
                                </div>
                        </div>
                    </div>
                    </div>';
        } else {
            $type = $explode[0];
            $search = $explode[1];
            $data = [];
            $index = '';
            switch ($type) {
                case 'cat':
                    $data = SN_Categories::search($search);
                    $index = 'category_name';
                    break;
                case 'post':
                case 'page':
                    $data = SN_Posts::search($search, $type);
                    $index = 'post_title';
                    break;
                case 'comm':
                    $data = SN_Comments::search($search);
                    $index = 'comment_contents';
                    break;
                    break;
                case 'user':
                    $data = SN_Users::search($search);
                    $index = 'user_name';
                    break;
                case 'term':
                    $data = SN_Terms::search($search);
                    $index = 'term_name';
                    break;
            }

            if ($data) {
                $page = filter_input(INPUT_POST, 'paged') ? $_POST['paged'] : 1;
                $arg = pagedNavArg([
                    'pagedNow' => $page,
                    'countData' => count($data),
                ]);

                $arg['getPost'] = "search=$str";

                $str = '';
                for ($i = $arg['beginRow']; $i < $arg['endRow']; ++$i) {
                    $str .= '<li>' . $data[$i][$index] . '</li>';
                }
                ?>
                <div id="header" class="clearfix">
                    <br/>
                    <h1>Resultado de la busqueda:</h1>
                </div>
                <div id="dataSearch">
                    <?php pagedNav($arg); ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="contentPost">
                                <ul>
                                    <?php echo $str; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php pagedNav($arg); ?>
                </div>
                <?php
                get_credits();
            } else {
                echo '<div id="header" class="clearfix">
                    <br/>
                    <h1>Resultado de la busqueda:</h1>
                </div>
                <div id="dataSearch">
                <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="contentPost">
                                <p>Datos de busqueda incorrectos.</p>
                                </div>
                        </div>
                    </div>
                    </div>';
            }
        }
    }
} else {
    echo '<div id="header" class="clearfix">
                    <br/>
                    <h1>Resultado de la busqueda:</h1>
                </div>
                <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="contentPost">
                            <p>No tienes suficientes permisos para realizar esta acción.</p>
                            </div>
                        </div>
                    </div>
                    </div>';
}
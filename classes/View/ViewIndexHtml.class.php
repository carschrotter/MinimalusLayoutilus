<?php

namespace mnhcc\ml\classes\View {
    use mnhcc\ml\classes;
    use mnhcc\ml\classes\Control\ControlIndex;

    /**
     * Default index view — renders the localised project introduction.
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     */
    class ViewIndexHtml extends classes\View
    {
        public function renderComponent(classes\ParmsControl $parm)
        {
            $i18n = ControlIndex::getI18n();
            ob_start();
            ?>
            <div class="jumbotron">
                <h1><?= $i18n['title'] ?></h1>
                <p class="lead"><?= $i18n['tagline'] ?></p>
                <p><?= $i18n['intro'] ?></p>
                <p>
                    <a href="https://github.com/carschrotter/MinimalusLayoutilus"
                       class="btn btn-primary btn-lg">
                        <span class="glyphicon glyphicon-cloud-download"></span>
                        <?= $i18n['github'] ?>
                    </a>
                </p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h2><?= $i18n['features_h'] ?></h2>
                    <ul class="list-unstyled">
                        <?php foreach ($i18n['features'] as $feature): ?>
                        <li>
                            <span class="glyphicon glyphicon-ok"
                                  style="color:#337ab7;margin-right:6px;"></span><?= $feature ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h2><?= $i18n['install_h'] ?></h2>
                    <div style="background:#222;color:#fff;border-radius:4px;padding:14px 20px;font-family:monospace;font-size:1.1em;">
                        $ <?= $i18n['install_cmd'] ?>
                    </div>
                    <p style="margin-top:10px;">
                        <span class="label label-default"><?= $i18n['license'] ?></span>
                        <span class="label label-info">PHP &ge; 5.4</span>
                        <span class="label label-success">v0.9</span>
                    </p>
                </div>
            </div>
            <?php
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        public function __call($name, $args)
        {
            return "<!-- no View $name -->";
        }
    }
}

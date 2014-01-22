<?php

namespace mnhcc\ml\classes\view;

use mnhcc\ml\classes as classes; {

    /**
     * Description of Control
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package Tasktus	 
     */
    class ViewIndexHtml extends classes\View {

        public function renderComponent(classes\ParmsControl $parm) {
            $base = classes\SERVER::getBase();
            ob_start();
            if ($_SERVER['SERVER_NAME'] == "myserver.mn-hegenbarth.de") {
                echo '<div class="alert alert-info">
				<h1 class="nospace"><a id="">Willkommen</a></h1>
				<h2>auf meinen Server</h2>
				<p>
Das hier ist das Eingangstor zu all meinen Spannenden Seiten, oder der von Verwandten und Bekannten.</p></div>
				<p>
					<a href="http://www.mn-hegenbarth.de/" class="btn btn-primary btn-large">Meine Website &raquo;</a>
				</p>';
            } else {
                echo '<div class="alert alert-block">	
				<h1 class="nospace"><a id="">Willkommen</a></h1>
				<h2>auf meinen Server</h2><p>
					Unter "' . $_SERVER['SERVER_NAME'] . '" existiert leider noch kein eigenständige Internet-Präsenz.
				<p>
					<a href="http://www.mn-hegenbarth.de/" class="btn btn-primary btn-large">Meine Website &raquo;</a>
				</p>';
            }
            $string = ob_get_contents();
            ob_end_clean();
            return $string;
        }
        
        public function __call($name, $args) {
            return "<!-- no View $name -->";
        }
        
    }

}
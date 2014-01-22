<?php
if(!\defined("INDEX")) die();
switch ($_GET['error']) {
    case 404:
        echo '
<h1 class="nospace"><a id="">404</a></h1>
<div class="alert alert-error">	
<pre class="ascii-art" style="background:inherit; border:0; color:inherit;">
' . file_get_contents('./ascii_404.txt') . '
</pre>
                    <h2>dieser Pfad führt ins leere</h2>
<p>
Leider hast du eine Adresse aufgerufen die…
<ol>
<li>…gerade nicht verfügbar ist</li>
<li>…nicht mehr existiert</li>
<li>…nie existierte</li>
<li>…oder wo <b>du</b> einfach nicht rein sollst</li>
</ol>

</p></div>
                    <p>
                            <a href="http://www.mn-hegenbarth.de/" class="btn btn-primary btn-large">Meine Website &raquo;</a>
                    </p>';
        break;
}

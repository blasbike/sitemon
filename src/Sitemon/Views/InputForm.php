<?php
    include 'Header.php';
?>

    <section>
        <form action="?action=benchmark/run" method="get">
            <input type="hidden" name="action" value="benchmark/run">
            <div class="span4">
                <label>Site to test (URL):</label>
                <input name="params[url]" type="text" size="40" maxlength="40">
            </div>
            <div class="span4">
                <label>Sites to compare to (one URL per line):</label>
                <textarea name="params[otherurls]" cols="31" rows="10"></textarea>
            </div>
            <div class="span4">
                <input type="submit" value="Submit">
            </div>
        </form>
    </section>
<?php
    include 'Footer.php';

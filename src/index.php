<?php
echo file_get_contents(realpath(join("/", [__DIR__, "public", "vue", "index.html"])));

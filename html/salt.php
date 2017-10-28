<?php
echo ($salt = mt_rand()) . "<br/>";
echo hash_hmac('sha1', 'amychow', 1221293185) ?>
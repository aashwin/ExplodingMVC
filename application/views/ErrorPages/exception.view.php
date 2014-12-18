<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: exception.view.php
 * Date: 11/11/14
 * Time: 20:17
 */

$viewArray=$this->getViewArray();
echo '<h1>Exception Page</h1>';
echo $viewArray['Exception'];
echo '<a href="'.Functions::pageLink('Index', 'Index').'">Go Back To Homepage</a>';
?>
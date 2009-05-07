<?php
$classes = array();
if ( $firstChild ) {
	$classes[] = 'first';
}
if ( $lastChild ) {
	$classes[] = 'last';
}
if ( $this->here == $data['url'] ) {
	$classes[] = 'current';
}
echo $html->link($data['title'], $data['url'], array('class' => implode(' ', $classes)));
?>
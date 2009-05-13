<?php
$classes = array();
if ( $firstChild ) {
	$classes[] = 'first';
}
if ( $lastChild ) {
	$classes[] = 'last';
}
if ( $this->here == $data['Node']['url'] ) {
	$classes[] = 'current';
}
if ( $data['Node']['url'] ) {
	echo $html->link($data['Node']['menu_title'], $data['Node']['url'], array('class' => implode(' ', $classes)));
}
else {
	echo $data['Node']['title'];
}
if ( !$lastChild ) {
	echo ' | ';
}
?>
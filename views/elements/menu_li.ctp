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
	$atts =  array(
		'id' => $data['Node']['slug'],
		'class' => implode(' ', $classes)
	);
	echo $html->link($data['Node']['menu_title'], $data['Node']['url'], $atts);
}
else {
	echo $data['Node']['title'];
}
?>
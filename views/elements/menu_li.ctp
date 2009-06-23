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
	echo $html->link('<span>' . $data['Node']['menu_title'] . '</span>', $data['Node']['url'], $atts, false, false);
}
else {
	echo '<span>' . $data['Node']['title'] . '</span>';
}
?>
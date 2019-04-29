<?php
// source: /var/www/access/app/presenters/templates/CardReaders/showList.latte

class Template6a073c3a61b86e981eeb235615e9499d extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('d44f8ead79', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb96e9cbe0f8_content')) { function _lb96e9cbe0f8_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;call_user_func(reset($_b->blocks['scripts']), $_b, get_defined_vars())  ?>

<div id="dialog" title="Confirmation Required">
  Opravdu opravdu toto chcete kartu a jeji zaznamy smazat?
</div>

<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← back to Home</a></p>
<h1>Seznam čteček karet</h1>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("CardReaders:showNewCardReader"), ENT_COMPAT) ?>
"> Nová čtečka karet</a></p>
<table>
	<tr align="left">
	<th> ID </th>
	<th> Adresa </th>
	<th> Komentář </th>
	<th> Směr přístupu</th>
	</tr>
	
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($cardReaders) as $cardReader) { ?>
	<tr <?php if ($_l->tmp = array_filter(array($iterator->isOdd() ? 'odd' : 'even'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($cardReader->id, ENT_NOQUOTES) ?></td>
		<td><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("CardReaders:showCardReader", array($cardReader->id)), ENT_COMPAT) ?>
"><?php echo Latte\Runtime\Filters::escapeHtml($cardReader->address, ENT_NOQUOTES) ?></a></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($cardReader->comment, ENT_NOQUOTES) ?></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($cardReader->direction, "1", "dovnitř"), "0", "ven"), ENT_NOQUOTES) ?></td>
		<td><a class="confirmLink" href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("CardReaders:deleteCardReader", array($cardReader->id)), ENT_COMPAT) ?>">smazat</a></td>
	</tr>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
</table>
<hr>
PS: Seznam čteček karet se nastavuje při zprovozňování systému, poté již není potřeba zde nic měnit. 
<?php
}}

//
// block scripts
//
if (!function_exists($_b->blocks['scripts'][] = '_lbd9e8ce8b4a_scripts')) { function _lbd9e8ce8b4a_scripts($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?>	<script src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/js/jquery.min.js"></script>
	<link rel="stylesheet" href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/css/jquery-ui.css">
	<script src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/js/jquery-ui.js"></script>
	<script src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/js/confirmLink.js"></script>
<?php
}}

//
// end of blocks
//

// template extending

$_l->extends = empty($_g->extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $_g->extended = TRUE;

if ($_l->extends) { ob_start(function () {});}

// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIRuntime::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['content']), $_b, get_defined_vars()) ; 
}}
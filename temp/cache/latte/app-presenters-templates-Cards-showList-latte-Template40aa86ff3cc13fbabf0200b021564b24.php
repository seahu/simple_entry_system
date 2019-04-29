<?php
// source: /var/www/access/app/presenters/templates/Cards/showList.latte

class Template40aa86ff3cc13fbabf0200b021564b24 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('b83c1a2256', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb9dbcfbc517_content')) { function _lb9dbcfbc517_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;call_user_func(reset($_b->blocks['scripts']), $_b, get_defined_vars())  ?>

<div id="dialog" title="Confirmation Required">
  Opravdu opravdu toto chcete kartu a jeji zaznamy smazat?
</div>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← back to Home</a></p>
<h1>Seznam karet</h1>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:showNewCard"), ENT_COMPAT) ?>
"> Nova karta</a></p>
<?php $_l->tmp = $_control->getComponent("cardSearchForm"); if ($_l->tmp instanceof Nette\Application\UI\IRenderable) $_l->tmp->redrawControl(NULL, FALSE); $_l->tmp->render() ?>
<table>
	<tr align="left">
	<th> kód karty </th>
	<th> Jméno </th>
	<th> Přístup </th>
	<th> Status </th>
	<th> Vložit příchod </th>
	<th> Vložit odchod </th>
	<th> Smazat </th>
	</tr>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($cards) as $card) { ?>
	<tr <?php if ($_l->tmp = array_filter(array($iterator->isOdd() ? 'odd' : 'even'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
		<td><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:showCard", array($card->id)), ENT_COMPAT) ?>
"><?php echo Latte\Runtime\Filters::escapeHtml($card->card_code, ENT_NOQUOTES) ?></a></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($card->name, ENT_NOQUOTES) ?></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($card->access, "1", "povoleno"), "0", "zakazano"), ENT_NOQUOTES) ?></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($card->status, "1", "přítomen"), "0", "nepřítomen"), ENT_NOQUOTES) ?></td>
		<!--<td><a class="confirmLink" href="<?php echo Latte\Runtime\Filters::escapeHtmlComment($_control->link("Cards:showConfirmationDeleteCard", array($card->id))) ?>">smazat</a></td>-->
		<td><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:InsertCardEntry", array($card->id)), ENT_COMPAT) ?>">vložit příchod</a></td>
		<td><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:InsertCardExit", array($card->id)), ENT_COMPAT) ?>">vložit odchod</a></td>
		<td><a class="confirmLink" href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:deleteCard", array($card->id)), ENT_COMPAT) ?>">smazat</a></td>
	</tr>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
</table>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:ShowCsvList"), ENT_COMPAT) ?>
">← csv</a></p>
<?php Tracy\Debugger::barDump(get_defined_vars(), 'variables') ;
}}

//
// block scripts
//
if (!function_exists($_b->blocks['scripts'][] = '_lb9b637ce400_scripts')) { function _lb9b637ce400_scripts($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
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
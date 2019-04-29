<?php
// source: /var/www/access/app/presenters/templates/History/showHistory.latte

class Templatee4536aa8909b339c5e57d11a45d48315 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('626a919cf3', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lbfdde80b576_content')) { function _lbfdde80b576_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;call_user_func(reset($_b->blocks['scripts']), $_b, get_defined_vars())  ?>


<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← back to Home</a></p>
<h1>Historie</h1>
<?php $_l->tmp = $_control->getComponent("filterForm"); if ($_l->tmp instanceof Nette\Application\UI\IRenderable) $_l->tmp->redrawControl(NULL, FALSE); $_l->tmp->render() ?>

<table>
	<tr align="left">
	<th> Čas </th>
	<th> kód karty </th>
	<th> jméno </th>
	<th> název čtečky </th>
	<th> směr </th>
	</tr>
	
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($history) as $row) { ?>
	<tr <?php if ($_l->tmp = array_filter(array($iterator->isOdd() ? 'odd' : 'even'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($row->timestamp, ENT_NOQUOTES) ?></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($row->card->card_code, ENT_NOQUOTES) ?></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($row->card->name, ENT_NOQUOTES) ?>
 <span style="font-size: smaller;"><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("History:ShowNearHistory", array($row->card->id)), ENT_COMPAT) ?>
"> nedávna historie </a></span></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($row->card_reader->comment, ENT_NOQUOTES) ?></td>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($row->card_reader->direction, "1", "dovnitř"), "0", "ven"), ENT_NOQUOTES) ?></td>
	</tr>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
</table>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("History:ShowHistoryCsvList"), ENT_COMPAT) ?>
">← csv</a></p>
<?php
}}

//
// block scripts
//
if (!function_exists($_b->blocks['scripts'][] = '_lbe9c40cd118_scripts')) { function _lbe9c40cd118_scripts($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
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
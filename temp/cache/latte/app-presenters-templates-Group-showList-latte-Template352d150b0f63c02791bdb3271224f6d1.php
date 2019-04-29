<?php
// source: /var/www/access/app/presenters/templates/Group/showList.latte

class Template352d150b0f63c02791bdb3271224f6d1 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('b770b745e4', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lbba1600ec27_content')) { function _lbba1600ec27_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;call_user_func(reset($_b->blocks['scripts']), $_b, get_defined_vars())  ?>

<div id="dialog" title="Confirmation Required">
  Opravdu opravdu toto chcete kartu a jeji zaznamy smazat?
</div>

<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← Zpět domu</a></p>
<h1>Seznam skupin</h1>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Group:showNewGroup"), ENT_COMPAT) ?>
"> Přidat novou skupinu</a></p>
<table>
	<tr align="left">
	<th> Název </th>
	<th> Přítomní </th>
	<th>  </th>
	<th>  </th>
	<th>  </th>
	</tr>
	
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($groups) as $group) { ?>
	<tr <?php if ($_l->tmp = array_filter(array($iterator->isOdd() ? 'odd' : 'even'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
		<td><?php echo Latte\Runtime\Filters::escapeHtml($group->name, ENT_NOQUOTES) ?></td>
		<td>  (<?php echo Latte\Runtime\Filters::escapeHtml($arr[$group->id]["perc"], ENT_NOQUOTES) ?>
%) <?php echo Latte\Runtime\Filters::escapeHtml($arr[$group->id]["in"], ENT_NOQUOTES) ?>
 z <?php echo Latte\Runtime\Filters::escapeHtml($arr[$group->id]["all"], ENT_NOQUOTES) ?> </td>
		<td><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Group:showGroup", array($group->id)), ENT_COMPAT) ?>">seznam členů</a></td>
		<td><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Group:showEditGroupName", array($group->id)), ENT_COMPAT) ?>">prejmenovat</a></td>
		<td><a class="confirmLink" href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Group:deleteGroup", array($group->id)), ENT_COMPAT) ?>">smazat</a></td>
	</tr>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
</table>
<?php Tracy\Debugger::barDump(get_defined_vars(), 'variables') ;
}}

//
// block scripts
//
if (!function_exists($_b->blocks['scripts'][] = '_lb0017371bcc_scripts')) { function _lb0017371bcc_scripts($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
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
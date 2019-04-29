<?php
// source: /var/www/access/app/presenters/templates/Group/showGroup.latte

class Template0a21786d91fb3671cec27b3de09f8d0f extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('68d304cb4b', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lbc4b23b57b8_content')) { function _lbc4b23b57b8_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← Zpět domu</a> <a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Group:showList"), ENT_COMPAT) ?>
">← Zpět na seznam skupin</a></p>
<h1>Skupina: <b><?php echo Latte\Runtime\Filters::escapeHtml($groups->name, ENT_NOQUOTES) ?></b></h1>
<table><tr>
	<td>
		<h1>Seznam členů:</h1>
			<table>
				<tr align="left">
				<th> Kód karty </th>
				<th> Název </th>
				<th> Status </th>
				<th> akce </th>
				</tr>
				
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($group_list) as $entry) { ?>
				<tr <?php if ($_l->tmp = array_filter(array($iterator->isOdd() ? 'odd' : 'even'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
					<td><?php echo Latte\Runtime\Filters::escapeHtml($entry->card->card_code, ENT_NOQUOTES) ?></td>
					<td><?php echo Latte\Runtime\Filters::escapeHtml($entry->card->name, ENT_NOQUOTES) ?></td>
					<td><?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($entry->card->status, "1", "přítomen"), "0", "nepřítomen"), ENT_NOQUOTES) ?></td>
					<td> <a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Group:delCardFromGroup", array($entry->card->id, $groups->id, $searchText)), ENT_COMPAT) ?>"> odebrat </a></td>
				</tr>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
			</table>
	</td>
	<td>
<?php $_l->tmp = $_control->getComponent("cardSearchForm"); if ($_l->tmp instanceof Nette\Application\UI\IRenderable) $_l->tmp->redrawControl(NULL, FALSE); $_l->tmp->render() ?>
		<hr>
		<table>
			<tr align="left">
			<th> akce </th>
			<th> kód karty </th>
			<th> Jméno </th>
			</tr>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($cards) as $card) { ?>
			<tr <?php if ($_l->tmp = array_filter(array($iterator->isOdd() ? 'odd' : 'even'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
				<td><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Group:addCardIntoGroup", array($card->id, $groups->id, $searchText)), ENT_COMPAT) ?>"> přidat </a></td>
				<td><?php echo Latte\Runtime\Filters::escapeHtml($card->card_code, ENT_NOQUOTES) ?></td>
				<td><?php echo Latte\Runtime\Filters::escapeHtml($card->name, ENT_NOQUOTES) ?></td>
			</tr>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
		</table>
	</td>
</tr></table>




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
<?php
// source: /var/www/access/app/presenters/templates/Homepage/default.latte

class Templatea20f7869054f77df4155908280fb64bf extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('23fb410ae6', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb3e689b091d_content')) { function _lb3e689b091d_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><h1>
	<a href="/seahu"><img src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/images/logo_v2.svg" id="img_logo" width="80px"></a> 
	Jenoduchý vstupní systém pro školy 
	<a href="http://www.spsstavrbno.cz"><img src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/images/spss-logo.svg" id="img_logo" width="100px"></a>
<?php if ($relay2) { ?>
		<a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:control", array('status' => $relay2)), ENT_COMPAT) ?>
"> <img src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/images/fire.png" width="30px"> </a>
<?php } else { ?>
		<a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:control", array('status' => $relay2)), ENT_COMPAT) ?>
"> <img src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/images/fire-no.png" width="30px"></a>
<?php } ?>
</h1>
<table>
	<tr>
		<td><h2><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:showList"), ENT_COMPAT) ?>">Karty</a></h2></td>
		<td><h2><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Group:showList"), ENT_COMPAT) ?>">Skupiny</a></h2></td>
		<td><h2><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("History:showHistory"), ENT_COMPAT) ?>">Historie</a></h2></td>
		<td><h2><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Edookit:"), ENT_COMPAT) ?>">Edookit</a></h2></td>
		<td><h2><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("CardReaders:showList"), ENT_COMPAT) ?>">Čtečky karet</a></h2></td>
		<td><h2><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Config:"), ENT_COMPAT) ?>">Konfigurace</a></h2></td>
		<td><h2><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Action:showEnter"), ENT_COMPAT) ?>">Akce</a></h2></td>
	</tr>
	<tr>
		<td>
			Počet karet: <?php echo Latte\Runtime\Filters::escapeHtml($number_all, ENT_NOQUOTES) ?><br>
			<a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Cards:showListPritomni"), ENT_COMPAT) ?>
">Přítomných: <?php echo Latte\Runtime\Filters::escapeHtml($number_in, ENT_NOQUOTES) ?> </a>
		</td>
		<td>Počet skupin: <?php echo Latte\Runtime\Filters::escapeHtml($number_groups, ENT_NOQUOTES) ?></td>
		<td>Počet záznamů: <?php echo Latte\Runtime\Filters::escapeHtml($number_logs, ENT_NOQUOTES) ?></td>
		<td>Počet záznamů k synchronizaci: <?php echo Latte\Runtime\Filters::escapeHtml($number_logs_need_sync, ENT_NOQUOTES) ?></td>
		<td>Počet čteček: <?php echo Latte\Runtime\Filters::escapeHtml($number_card_readers, ENT_NOQUOTES) ?></td>
		<td></td>
		<td></td>
	</tr>
</table>
<table>
	<tr>
		<td width="50%" valign="top">
			<h2>Příchody</h2>
			<table>
				<tr>
					<th>Čas</th>
					<th>Jméno</th>
				</tr>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($entry) as $row) { ?>
					<tr <?php if ($_l->tmp = array_filter(array($iterator->isOdd() ? 'odd' : 'even'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
						<td><?php echo Latte\Runtime\Filters::escapeHtml($row->timestamp, ENT_NOQUOTES) ?></td>
						<td><?php echo Latte\Runtime\Filters::escapeHtml($row->card->name, ENT_NOQUOTES) ?></td>
					</tr>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
			</table>
		</td>
		<td valign="top">
			<h2>Odchody</h2>
			<table>
				<tr>
					<th>Čas</th>
					<th>Jméno</th>
				</tr>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($exits) as $row) { ?>
					<tr <?php if ($_l->tmp = array_filter(array($iterator->isOdd() ? 'odd' : 'even'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
						<td><?php echo Latte\Runtime\Filters::escapeHtml($row->timestamp, ENT_NOQUOTES) ?></td>
						<td><?php echo Latte\Runtime\Filters::escapeHtml($row->card->name, ENT_NOQUOTES) ?></td>
					</tr>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
			</table>
		</td>
	</tr>
</table>

<div>
	<a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/adminer/">tool Adminer</a>
</div>

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
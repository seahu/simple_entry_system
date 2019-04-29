<?php
// source: /var/www/access/app/presenters/templates/History/showNearHistory.latte

class Template2e45a2b365630226d799daafe811f5b7 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('4dc85b0447', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lba270030368_content')) { function _lba270030368_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><h1>Nedavna historie: <?php echo Latte\Runtime\Filters::escapeHtml($card->name, ENT_NOQUOTES) ?></h1>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← back to Home</a> <a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("History:showHistory"), ENT_COMPAT) ?>
">← Zpět na prohlížení historie</a></p>
<table>
	<tr>
		<td width='100px'>datum/čas</td>
		<td align="left" valign="top">
			<div style="position: relative;height: 30px;">
<?php for ($x=0; $x<1440; $x=$x+60) { ?>
				<span style="left: <?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss($x), ENT_COMPAT) ?>
px;"<?php if ($_l->tmp = array_filter(array($x%120==0 ? 'sudaHodina' : 'lichaHodina'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>></span>
<?php } for ($x=0; $x<24; $x=$x+1) { ?>
				<span style="position: absolute; left: <?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss($x*60), ENT_COMPAT) ?>
px;"><?php echo Latte\Runtime\Filters::escapeHtml($x, ENT_NOQUOTES) ?></span>
<?php } ?>
			</div>
		</td>
	</tr>
<?php $iterations = 0; foreach ($arr as $day => $times) { ?>
	<tr>
		<td> <?php echo Latte\Runtime\Filters::escapeHtml($day, ENT_NOQUOTES) ?> </td>
			
		<td>
			<div style="position: relative;height: 30px;">
<?php for ($x=0; $x<1440; $x=$x+60) { ?>
					<span style="left: <?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss($x), ENT_COMPAT) ?>
px;"<?php if ($_l->tmp = array_filter(array($x%120==0 ? 'sudaHodina' : 'lichaHodina'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>></span>
<?php } ?>
				
<?php $iterations = 0; foreach ($times['graf'] as $graf) { ?>
					<div class="pritomen" style="left: <?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss((int)(1440*$graf['start']/1440)), ENT_COMPAT) ?>
px; width: <?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss((int)(1440*($graf['end']-$graf['start'])/1440)), ENT_COMPAT) ?>px;">
					</div>
<?php $iterations++; } ?>
	
<?php $iterations = 0; foreach ($times['timestamps'] as $time) { ?>
					<div style="left: <?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss((int)((1440*$time['min']/1440)-2)), ENT_COMPAT) ?>
px;" title="<?php echo Latte\Runtime\Filters::escapeHtml($time['time'], ENT_COMPAT) ?>
"<?php if ($_l->tmp = array_filter(array($time['direction']==1 ? 'prichod' : 'odchod'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
					</div>
<?php $iterations++; } ?>
			</div>
		</td>
	</tr>
<?php $iterations++; } ?>
</table><?php
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
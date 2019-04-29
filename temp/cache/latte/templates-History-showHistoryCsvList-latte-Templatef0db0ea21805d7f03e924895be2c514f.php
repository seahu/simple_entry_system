<?php
// source: /var/www/access/app/presenters/templates/History/showHistoryCsvList.latte

class Templatef0db0ea21805d7f03e924895be2c514f extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('b47b98d248', 'html')
;
// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIRuntime::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
$iterations = 0; foreach ($history as $row) { ?>
	<?php echo Latte\Runtime\Filters::escapeHtml($row->timestamp, ENT_NOQUOTES) ?>,<?php echo Latte\Runtime\Filters::escapeHtml($row->card->card_code, ENT_NOQUOTES) ?>
,<?php echo Latte\Runtime\Filters::escapeHtml($row->card->name, ENT_NOQUOTES) ?>
,<?php echo Latte\Runtime\Filters::escapeHtml($row->card_reader->comment, ENT_NOQUOTES) ?>
,<?php echo Latte\Runtime\Filters::escapeHtml($template->replace($template->replace($row->card_reader->direction, "1", "dovnitř"), "0", "ven"), ENT_NOQUOTES) ?> <br>
<?php $iterations++; } 
}}
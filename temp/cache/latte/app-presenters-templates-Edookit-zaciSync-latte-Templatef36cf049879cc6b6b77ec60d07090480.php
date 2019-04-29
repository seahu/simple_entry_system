<?php
// source: /var/www/access/app/presenters/templates/Edookit/zaciSync.latte

class Templatef36cf049879cc6b6b77ec60d07090480 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('5869802571', 'html')
;
// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIRuntime::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
?>
<h1>Edookit synchronizace zaku</h1>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← Zpět domu</a> <a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Edookit:"), ENT_COMPAT) ?>
">← Zpět na seznam skupin</a></p>

Skola: <?php echo Latte\Runtime\Filters::escapeHtml($SkolaNazev, ENT_NOQUOTES) ?> <br>
Skolni rok: <?php echo Latte\Runtime\Filters::escapeHtml($SkolniRok, ENT_NOQUOTES) ?><br>
Pololeti: <?php echo Latte\Runtime\Filters::escapeHtml($Pololeti, ENT_NOQUOTES) ?> <br>
Pocet trid: <?php echo Latte\Runtime\Filters::escapeHtml($pocetTrid, ENT_NOQUOTES) ?> <br>
Pocet novych zaznamu: <?php echo Latte\Runtime\Filters::escapeHtml($new, ENT_NOQUOTES) ?> <br>
Pocet editovanych zaznamu: <?php echo Latte\Runtime\Filters::escapeHtml($edit, ENT_NOQUOTES) ?>
 <br><?php
}}
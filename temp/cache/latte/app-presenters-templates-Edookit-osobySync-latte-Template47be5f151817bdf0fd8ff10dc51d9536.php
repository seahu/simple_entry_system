<?php
// source: /var/www/access/app/presenters/templates/Edookit/osobySync.latte

class Template47be5f151817bdf0fd8ff10dc51d9536 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('b4a64ce124', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb1b11eb754a_content')) { function _lb1b11eb754a_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><h1>Edookit synchronizace zaku</h1>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← Zpět domu</a> <a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Edookit:"), ENT_COMPAT) ?>
">← Zpět do menu Edookit</a></p>

Skola: <?php echo Latte\Runtime\Filters::escapeHtml($SkolaNazev, ENT_NOQUOTES) ?> <br>
Skolni rok: <?php echo Latte\Runtime\Filters::escapeHtml($SkolniRok, ENT_NOQUOTES) ?><br>
Pololeti: <?php echo Latte\Runtime\Filters::escapeHtml($Pololeti, ENT_NOQUOTES) ?> <br>
Pocet trid zaku: <?php echo Latte\Runtime\Filters::escapeHtml($pocetTrid, ENT_NOQUOTES) ?> <br>
Pocet novych zaznamu zaku: <?php echo Latte\Runtime\Filters::escapeHtml($new, ENT_NOQUOTES) ?> <br>
Pocet editovanych zaznamu zaku: <?php echo Latte\Runtime\Filters::escapeHtml($edit, ENT_NOQUOTES) ?> <br>
<br>
Pocet novych zaznamu pracovniku: <?php echo Latte\Runtime\Filters::escapeHtml($new_pracovnici, ENT_NOQUOTES) ?> <br>
Pocet editovanych zaznamu pracovniku: <?php echo Latte\Runtime\Filters::escapeHtml($edit_pracovnici, ENT_NOQUOTES) ?> <br>
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
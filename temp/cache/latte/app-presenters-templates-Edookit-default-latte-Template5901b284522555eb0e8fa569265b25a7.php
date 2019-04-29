<?php
// source: /var/www/access/app/presenters/templates/Edookit/default.latte

class Template5901b284522555eb0e8fa569265b25a7 extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('04a6f117ff', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb5f5a38da2e_content')) { function _lb5f5a38da2e_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><h1>Edookit</h1>
<p><a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Homepage:default"), ENT_COMPAT) ?>
">← Zpět domu</a></p>
Skola: <?php echo Latte\Runtime\Filters::escapeHtml($SkolaNazev, ENT_NOQUOTES) ?> <br>
Skolni rok: <?php echo Latte\Runtime\Filters::escapeHtml($SkolniRok, ENT_NOQUOTES) ?><br>
Pololeti: <?php echo Latte\Runtime\Filters::escapeHtml($Pololeti, ENT_NOQUOTES) ?> <br>
<br>
<div>
	<a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Edookit:osobySync"), ENT_COMPAT) ?>"> Synchronizace osob EDOOKIT-->vstupni system  </a>
</div>
<div>
	<a href="<?php echo Latte\Runtime\Filters::escapeHtml($_control->link("Edookit:pruchodySync"), ENT_COMPAT) ?>"> Synchronizace průchodu vstupího systému se systémem EDOOKIT  </a>
</div>
<hr>
PS: Pokud je potřeba aktualizovat seznam studentu z edookitu stačí vybrat volbu <i>Synchronizace žáků EDOOKIT-->vstupni system</i>.<br>
Vetšinou jen na začátku školního roku, nebo když do školy nastoupí nový student.<br>
Synchonizace průchodu by se mněla provádět automaticky, proto volba <i>Synchronizace průchodu vstupího systému se systémem EDOOKIT</i><br>
je vhodná v přípdě, že automatická synchronizace selhala.<br>
Automaticka synchronizace se provadí externím programem, který periodicky otevíra výše zmíněný odkaz.<br>
Přístupové údaje k EDOOKITu se zadavaji v hlavním menu položka <i>Konfigurace</i>.
<br>


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
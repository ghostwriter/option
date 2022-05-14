<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\GroupImportFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::ARRAY);
    $containerConfigurator->import(SetList::CLEAN_CODE);
    $containerConfigurator->import(SetList::COMMON);
    $containerConfigurator->import(SetList::CONTROL_STRUCTURES);
    $containerConfigurator->import(SetList::NAMESPACES);
    $containerConfigurator->import(SetList::PHP_CS_FIXER);
    $containerConfigurator->import(SetList::PHPUNIT);
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::PHP_CS_FIXER_RISKY);
    $containerConfigurator->import(SetList::SPACES);
    $containerConfigurator->import(SetList::STRICT);
    $containerConfigurator->import(SetList::SYMPLIFY);
//    $containerConfigurator->import(SetList::SYMFONY);
//    $containerConfigurator->import(SetList::SYMFONY_RISKY);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PARALLEL, true);
    $parameters->set(Option::CACHE_DIRECTORY, '.cache/.ecs');
    $parameters->set(
        Option::PATHS,
        [__DIR__ . '/rector.php', __DIR__ . '/ecs.php', __DIR__ . '/bin', __DIR__ . '/src', __DIR__ . '/tests']
    );
    $parameters->set(Option::SKIP, value: [
        '*/tests/Fixture/*',
        '*/vendor/*',
        GroupImportFixer::class,
        BinaryOperatorSpacesFixer::class,
        GeneralPhpdocAnnotationRemoveFixer::class,
        PhpdocLineSpanFixer::class,
        PhpdocTrimFixer::class,
    ]);

    $services = $containerConfigurator->services();

    $services->set(PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]])
    ;
    $services->set(PhpCsFixer\Fixer\Casing\ConstantCaseFixer::class)
        ->call('configure', [[
            'case' => 'lower',
        ]])
    ;
    $services->set(PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer::class);
    $services->set(PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer::class);
    $services->set(PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer::class);
    $services->set(PhpCsFixer\Fixer\Casing\MagicMethodCasingFixer::class);
    $services->set(PhpCsFixer\Fixer\ClassNotation\FinalClassFixer::class);
    $services->set(PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer::class)
        ->call('configure', [[
            'sort_algorithm' => 'alpha',
        ]])
    ;
    $services->set(PhpCsFixer\Fixer\ClassNotation\OrderedInterfacesFixer::class)
        ->call('configure', [[
            'order' => 'alpha',
        ]])
    ;
    $services->set(PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer::class);
    $services->set(PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer::class);
    $services->set(PhpCsFixer\Fixer\ClassNotation\SelfStaticAccessorFixer::class);
    $services->set(PhpCsFixer\Fixer\ClassNotation\SingleClassElementPerStatementFixer::class);
    $services->set(PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer::class);
    $services->set(PhpCsFixer\Fixer\ControlStructure\ElseifFixer::class);
    $services->set(PhpCsFixer\Fixer\ControlStructure\NoSuperfluousElseifFixer::class);
    $services->set(PhpCsFixer\Fixer\ControlStructure\SimplifiedIfReturnFixer::class);
    $services->set(PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer::class);
    $services->set(PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer::class);
    $services->set(PhpCsFixer\Fixer\FunctionNotation\StaticLambdaFixer::class);
    $services->set(PhpCsFixer\Fixer\FunctionNotation\UseArrowFunctionsFixer::class);
    $services->set(PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer::class);
    $services->set(PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer::class)
        ->call('configure', [[
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ]])
    ;
    $services->set(PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer::class);
    $services->set(PhpCsFixer\Fixer\Import\NoUnusedImportsFixer::class);
    $services->set(PhpCsFixer\Fixer\Import\OrderedImportsFixer::class)
        ->call('configure', [[
            'imports_order' => ['class', 'const', 'function'],
        ]])
    ;
    $services->set(PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer::class);
    $services->set(PhpCsFixer\Fixer\LanguageConstruct\GetClassToClassKeywordFixer::class);
    $services->set(PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer::class);
    $services->set(PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer::class)
        ->call('configure', [[
            'tags' => ['method', 'param', 'property', 'return', 'throws', 'type', 'var'],
        ]])
    ;
    $services->set(PhpCsFixer\Fixer\Phpdoc\PhpdocAnnotationWithoutDotFixer::class);
    $services->set(PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer::class);
    $services->set(PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer::class);
    $services->set(PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer::class);
    $services->set(PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitExpectationFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitFqcnAnnotationFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitInternalClassFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitMockFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitNamespacedFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitNoExpectationAnnotationFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitSizeClassFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer::class)
        ->call('configure', [[
            'call_type' => 'self',
        ]])
    ;
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer::class);
    $services->set(PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer::class);
    $services->set(PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer::class);
    $services->set(PhpCsFixer\Fixer\Semicolon\SemicolonAfterInstructionFixer::class);
    $services->set(PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer::class);
    $services->set(PhpCsFixer\Fixer\Strict\StrictComparisonFixer::class);
    $services->set(PhpCsFixer\Fixer\Strict\StrictParamFixer::class);
};

<?php

use PHPUnit\Framework\TestCase;

final class QuizQuestionTypesTest extends TestCase
{
    private function modelWithoutConstructor(): object
    {
        return (new ReflectionClass(App\Models\QuizModel::class))->newInstanceWithoutConstructor();
    }

    private function invoke(object $model, string $method, array $arguments): mixed
    {
        return (new ReflectionMethod($model, $method))->invokeArgs($model, $arguments);
    }

    public function testAllQuestionTypesAreSupportedAcrossAuthoringRuntimeAndScoring(): void
    {
        $model = file_get_contents(APPPATH . 'Models/QuizModel.php');
        $form = file_get_contents(APPPATH . 'Views/quiz/form.php');
        $runtime = file_get_contents(APPPATH . 'Views/quiz/show.php');

        foreach (['multi', 'multi_select', 'true_false', 'short_answer', 'fill_blank', 'sort_order', 'matching', 'numeric', 'text', 'file_upload'] as $type) {
            $this->assertStringContainsString($type, $model, "Model is missing {$type}");
            $this->assertStringContainsString($type, $form, "Authoring form is missing {$type}");
            $this->assertStringContainsString($type, $runtime, "Learner runtime is missing {$type}");
        }
    }

    public function testFileUploadsArePrivateValidatedAndDownloadedThroughAuthorizedRoute(): void
    {
        $controller = file_get_contents(APPPATH . 'Controllers/QuizPortal.php');
        $model = file_get_contents(APPPATH . 'Models/QuizModel.php');
        $routes = file_get_contents(APPPATH . 'Config/Routes.php');
        $this->assertStringContainsString("WRITEPATH . 'uploads/quiz_answers", $model);
        $this->assertStringContainsString('getMimeType()', $model);
        $this->assertStringContainsString('getSize()', $model);
        $this->assertStringContainsString("adminContext('ru_view')", $controller);
        $this->assertStringContainsString("quiz-answers/(:num)/download", $routes);
    }

    public function testExtendedQuestionSchemaMigrationIsIdempotent(): void
    {
        $migration = file_get_contents(ROOTPATH . '../database/migrations/20260810_quiz_question_types.sql');
        foreach (['ques_numeric_answer', 'ques_numeric_tolerance', 'ques_text_match_mode', 'information_schema.COLUMNS'] as $column) {
            $this->assertStringContainsString($column, $migration);
        }
    }

    public function testAutomaticScoringHandlesNewQuestionTypesAndEdgeCases(): void
    {
        $model = $this->modelWithoutConstructor();
        $this->assertSame(3.0, $this->invoke($model, 'scoreQuestion', [['ques_type'=>'multi_select','ques_score'=>3,'correct_answers'=>['mul_c1','mul_c3']], ['mul_c3','mul_c1']])['score']);
        $this->assertSame(0.0, $this->invoke($model, 'scoreQuestion', [['ques_type'=>'multi_select','ques_score'=>3,'correct_answers'=>['mul_c1','mul_c3']], ['mul_c1']])['score']);
        $this->assertSame(2.0, $this->invoke($model, 'scoreQuestion', [['ques_type'=>'matching','ques_score'=>2,'matching_pairs'=>[['value'=>'mul_c1'],['value'=>'mul_c2']]], ['mul_c1'=>'mul_c1','mul_c2'=>'mul_c2']])['score']);
        $this->assertSame(1.0, $this->invoke($model, 'scoreQuestion', [['ques_type'=>'numeric','ques_score'=>1,'ques_numeric_answer'=>10,'ques_numeric_tolerance'=>0.5], '10.5'])['score']);
        $this->assertSame(0.0, $this->invoke($model, 'scoreQuestion', [['ques_type'=>'numeric','ques_score'=>1,'ques_numeric_answer'=>10,'ques_numeric_tolerance'=>0.5], '10.51'])['score']);
        $this->assertSame(1.0, $this->invoke($model, 'scoreQuestion', [['ques_type'=>'short_answer','ques_score'=>1,'ques_text_match_mode'=>'exact','blank_answers'=>['Code Red']], ' code  red '])['score']);
    }

    public function testQuestionPayloadRejectsInvalidSpecializedConfiguration(): void
    {
        $model = $this->modelWithoutConstructor();
        $numeric = $this->invoke($model, 'questionPayload', [['ques_type'=>'numeric','ques_name_eng'=>'Value','ques_numeric_answer'=>'abc','ques_numeric_tolerance'=>'0']]);
        $matching = $this->invoke($model, 'questionPayload', [['ques_type'=>'matching','ques_name_eng'=>'Match','mul_c1_eng'=>'Only one ||| Pair']]);
        $this->assertFalse($numeric['ok']);
        $this->assertFalse($matching['ok']);
    }
}

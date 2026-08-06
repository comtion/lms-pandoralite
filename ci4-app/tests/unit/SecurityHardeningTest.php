<?php
use App\Libraries\ExportSanitizer;
use CodeIgniter\Test\CIUnitTestCase;
final class SecurityHardeningTest extends CIUnitTestCase
{
    public function testSpreadsheetFormulaInjectionIsNeutralized(): void
    {
        foreach(['=1+1','+cmd','-2+3','@SUM(A1:A2)',"\tformula"] as $value)$this->assertStringStartsWith("'",ExportSanitizer::cell($value));
        $this->assertSame('normal',ExportSanitizer::cell('normal'));
    }
    public function testAdminModuleEnforcesCompanyScope(): void
    {
        $source=file_get_contents(APPPATH.'Models/AdminModuleModel.php');
        $this->assertStringContainsString("where('com_id'", $source);
        $this->assertStringContainsString('Record is outside your company scope.', $source);
    }
    public function testPublicSurveyUsesEligibilityValidationTransactionsAndLocks(): void
    {
        $source=file_get_contents(APPPATH.'Models/PublicSurveyModel.php');
        foreach(['canAccess(','transBegin()','FOR UPDATE','allowed choices','already been completed'] as $needle)$this->assertStringContainsString($needle,$source);
    }
    public function testUploadsValidateMimeContentSizeAndDisableScripts(): void
    {
        $source=file_get_contents(APPPATH.'Controllers/AdminModule.php');
        foreach(['getMimeType()','getimagesize','20*1024*1024',"'%PDF-'"] as $needle)$this->assertStringContainsString($needle,$source);
        $this->assertFileExists(ROOTPATH.'../uploads/admin/.htaccess');
    }
}

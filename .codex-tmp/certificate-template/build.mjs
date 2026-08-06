import fs from "node:fs/promises";
import { SpreadsheetFile, Workbook } from "@oai/artifact-tool";

const outputDir = "outputs/certificate-template";
const publicDir = "uploads/format";
await fs.mkdir(outputDir, { recursive: true });
await fs.mkdir(publicDir, { recursive: true });

const workbook = Workbook.create();
const sheet = workbook.worksheets.add("Certificate Data");
sheet.showGridLines = false;

sheet.getRange("A1:G4").values = [
  ["Fullname", "Company", "Course", "Description 1", "Description 2", "Issued Date", "Language"],
  ["สมชาย ใจดี", "บริษัท อีซูซุมอเตอร์ (ประเทศไทย) จำกัด", "หลักสูตรตัวอย่าง", "ผ่านการอบรมเรียบร้อยแล้ว", "", "2026-08-06", "TH"],
  ["John Smith", "Isuzu Motors Co., (Thailand) Ltd.", "Sample Training Course", "", "", "2026-08-06", "EN"],
  ["山田 太郎", "いすゞ自動車株式会社", "サンプル研修コース", "", "", "2026-08-06", "JP"],
];

sheet.getRange("A1:G1").format = {
  fill: "#E63946",
  font: { bold: true, color: "#FFFFFF" },
  horizontalAlignment: "center",
  verticalAlignment: "center",
  wrapText: true,
  borders: { preset: "outside", style: "medium", color: "#B91C1C" },
};
sheet.getRange("A2:G4").format = {
  fill: "#FFF7F7",
  font: { color: "#1F2937" },
  verticalAlignment: "center",
  borders: { preset: "inside", style: "thin", color: "#E5E7EB" },
};
sheet.getRange("F2:F200").format.numberFormat = "yyyy-mm-dd";
sheet.getRange("G2:G200").dataValidation = {
  rule: { type: "list", values: ["TH", "EN", "JP"] },
};
sheet.getRange("A1:G4").format.rowHeight = 25;
sheet.getRange("A:A").format.columnWidth = 24;
sheet.getRange("B:B").format.columnWidth = 38;
sheet.getRange("C:C").format.columnWidth = 32;
sheet.getRange("D:E").format.columnWidth = 28;
sheet.getRange("F:F").format.columnWidth = 16;
sheet.getRange("G:G").format.columnWidth = 12;
sheet.freezePanes.freezeRows(1);

const check = await workbook.inspect({
  kind: "table",
  range: "Certificate Data!A1:G4",
  include: "values,formulas",
  tableMaxRows: 10,
  tableMaxCols: 10,
});
console.log(check.ndjson);

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 50 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

const preview = await workbook.render({
  sheetName: "Certificate Data",
  range: "A1:G4",
  scale: 2,
  format: "png",
});
await fs.writeFile(`${outputDir}/certificate_excel_preview.png`, new Uint8Array(await preview.arrayBuffer()));

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(`${outputDir}/certificate_excel.xlsx`);
await fs.copyFile(`${outputDir}/certificate_excel.xlsx`, `${publicDir}/certificate_excel.xlsx`);

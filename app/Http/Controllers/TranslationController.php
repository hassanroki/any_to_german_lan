<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Translation;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Barryvdh\DomPDF\Facade\Pdf;

class TranslationController extends Controller
{
    public function index()
    {
        $translations = Translation::latest()->paginate(5);
        return view('translate', compact('translations'));
    }

    public function translate(Request $request)
    {
        $request->validate([
            'text' => 'nullable|string',
            'chassi' => 'nullable|file|mimes:pdf,docx'
        ]);

        $originalText = $request->input('text');
        $fileName = null;
        $germanPdfName = null;

        if ($request->hasFile('chassi')) {
            $file = $request->file('chassi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            $ext = $file->getClientOriginalExtension();
            if ($ext === 'pdf') {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile(storage_path('app/public/' . $filePath));
                $originalText = $pdf->getText();
            } elseif ($ext === 'docx') {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load(storage_path('app/public/' . $filePath));
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                $originalText = $text;
            }
        }

        if (!$originalText) {
            return response()->json(['message' => 'No text found to translate.'], 400);
        }

        $tr = new GoogleTranslate('de');
        $translatedText = $tr->translate($originalText);

        $pdf = Pdf::loadView('german_pdf', ['translatedText' => $translatedText]);
        $germanPdfName = 'german_' . time() . '.pdf';
        Storage::disk('public')->put('generated/' . $germanPdfName, $pdf->output());

        Translation::create([
            'original_text' => $originalText,
            'translated_text' => $translatedText,
            'chassi' => $fileName,
            'german_pdf' => $germanPdfName,
        ]);

        $translations = Translation::latest()->paginate(5);
        $html = view('partials.translations', compact('translations'))->render();

        return response()->json([
            'message' => 'German PDF generated and saved!',
            'html' => $html
        ]);
    }
}

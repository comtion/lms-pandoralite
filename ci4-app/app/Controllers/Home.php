<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function changeLang(string $lang)
    {
        if (! in_array($lang, ['thai', 'english', 'japan'], true)) {
            $lang = 'english';
        }

        session()->set('lang', $lang);
        $user = session()->get('user');
        if (is_array($user) && ! empty($user['u_id'])) {
            db_connect()->table('lms_usp')->where('u_id', (int) $user['u_id'])->update(['lang_last' => $lang]);
        }

        return redirect()->back();
    }

    public function faq()
    {
        $lang = session()->get('lang') ?? 'english';
        $rows = db_connect()->table('lms_faq')
            ->where('lang', $lang)
            ->orderBy('id', 'DESC')
            ->limit(100)
            ->get()
            ->getResultArray();

        return view('home/simple_content', [
            'title' => 'FAQ',
            'rows' => array_map(static fn (array $row): array => [
                'title' => (string) ($row['title'] ?? '-'),
                'body' => (string) ($row['description'] ?? $row['detail'] ?? ''),
            ], $rows),
        ]);
    }

    public function privacyPolicy()
    {
        $lang = session()->get('lang') ?? 'english';
        $about = db_connect()->table('lms_about')->where('da_id', '1')->get()->getRowArray() ?: [];
        $field = match ($lang) {
            'thai' => 'da_privacy_policy_th',
            'japan' => 'da_privacy_policy_jp',
            default => 'da_privacy_policy_en',
        };

        return view('home/simple_content', [
            'title' => 'Privacy Policy',
            'rows' => [[
                'title' => 'Privacy Policy',
                'body' => (string) ($about[$field] ?? ''),
            ]],
        ]);
    }
}

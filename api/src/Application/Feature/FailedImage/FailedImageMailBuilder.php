<?php

declare(strict_types=1);

namespace App\Application\Feature\FailedImage;

use App\Api\Feature\Webhook\ResultResponse;

final readonly class FailedImageMailBuilder
{
    /**
     * @param ResultResponse[] $results
     */
    public function buildSubject(string $galleryName): string
    {
        return 'Pixario · Nie udało się przetworzyć zdjęć: ' . $galleryName;
    }

    /**
     * @param ResultResponse[] $results
     */
    public function buildHtml(string $galleryName, array $results): string
    {
        $safeGalleryName = htmlspecialchars(
            $galleryName,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        $failedItems = array_map(
            fn(ResultResponse $result) => sprintf(
                '
                <tr>
                    <td style="padding:14px 16px;border-bottom:1px solid #f1ecff;">
                        <div style="font-size:14px;font-weight:700;color:#17122b;">
                            %s
                        </div>
                        <div style="margin-top:6px;font-size:13px;line-height:1.5;color:#7a728c;">
                            %s
                        </div>
                    </td>
                </tr>
                ',
                htmlspecialchars(
                    $result->fileName,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                ),
                htmlspecialchars(
                    $result->errorMessage ?? 'Brak szczegółów błędu',
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                )
            ),
            $results
        );

        return sprintf(
            '
            <!DOCTYPE html>
            <html lang="pl">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Pixario</title>
            </head>

            <body style="margin:0;padding:0;background:#f8f7fb;font-family:Arial,sans-serif;color:#17122b;">
                <table width="100%%" cellpadding="0" cellspacing="0" style="background:#f8f7fb;padding:40px 16px;">
                    <tr>
                        <td align="center">
                            <table width="100%%" cellpadding="0" cellspacing="0" style="max-width:720px;background:#ffffff;border-radius:28px;overflow:hidden;box-shadow:0 24px 70px rgba(30,19,72,0.12);">

                                <tr>
                                    <td style="padding:48px;background:linear-gradient(135deg,#160b4b 0%%,#42158b 48%%,#bd82ef 100%%);color:#ffffff;">
                                        <div style="font-size:26px;font-weight:800;letter-spacing:-0.02em;">
                                            ✦ pixario
                                        </div>

                                        <div style="margin-top:32px;">
                                            <span style="display:inline-block;padding:8px 14px;border-radius:999px;background:#fff1f1;color:#d33b3b;font-size:12px;font-weight:700;">
                                                ✕ Przetwarzanie nieudane
                                            </span>
                                        </div>

                                        <h1 style="margin:18px 0 0;font-size:36px;line-height:1.1;font-weight:800;letter-spacing:-0.04em;color:#ffffff;">
                                            Nie udało się przetworzyć zdjęć
                                        </h1>

                                        <p style="margin:18px 0 0;max-width:520px;font-size:15px;line-height:1.7;color:rgba(255,255,255,0.82);">
                                            Żadne zdjęcie z tej galerii nie zostało poprawnie przetworzone przez Pixario AI.
                                            Poniżej znajdziesz listę błędów zwróconych podczas przetwarzania.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:36px;">

                                        <div style="padding:24px;border-radius:20px;background:#fbfaff;border:1px solid #eee8f7;">
                                            <div style="font-size:12px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#6f35e8;">
                                                Galeria
                                            </div>

                                            <div style="margin-top:10px;font-size:28px;font-weight:800;letter-spacing:-0.03em;color:#17122b;">
                                                %s
                                            </div>

                                            <table width="100%%" cellpadding="0" cellspacing="0" style="margin-top:24px;">
                                                <tr>
                                                    <td width="50%%" style="padding-right:8px;">
                                                        <div style="padding:18px;border-radius:16px;background:#ffffff;border:1px solid #eee8f7;text-align:center;">
                                                            <div style="font-size:32px;font-weight:800;color:#0d8a4b;">
                                                                0
                                                            </div>
                                                            <div style="margin-top:6px;font-size:13px;font-weight:700;color:#8a839b;">
                                                                Przetworzonych zdjęć
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td width="50%%" style="padding-left:8px;">
                                                        <div style="padding:18px;border-radius:16px;background:#ffffff;border:1px solid #eee8f7;text-align:center;">
                                                            <div style="font-size:32px;font-weight:800;color:#d33b3b;">
                                                                %d
                                                            </div>
                                                            <div style="margin-top:6px;font-size:13px;font-weight:700;color:#8a839b;">
                                                                Błędów
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div style="margin-top:32px;">
                                            <div style="margin-bottom:14px;font-size:18px;font-weight:800;color:#17122b;">
                                                Lista błędów
                                            </div>

                                            <table width="100%%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#ffffff;border:1px solid #eee8f7;border-radius:16px;overflow:hidden;">
                                                %s
                                            </table>
                                        </div>

                                        <div style="margin-top:28px;padding:18px 20px;border-radius:18px;background:#fff6df;border:1px solid #ffe4a3;color:#7a4e00;font-size:14px;line-height:1.6;">
                                            <strong>Co dalej?</strong><br>
                                            Sprawdź formaty plików, rozmiary zdjęć lub spróbuj ponownie przesłać obrazy do galerii.
                                        </div>

                                        <div style="margin-top:36px;padding-top:24px;border-top:1px solid #eee8f7;font-size:13px;line-height:1.7;color:#8a839b;text-align:center;">
                                            Ten e-mail został wygenerowany automatycznie przez Pixario AI.<br>
                                            © %d Pixario. Wszystkie prawa zastrzeżone.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>
            ',
            $safeGalleryName,
            count($results),
            implode('', $failedItems),
            (int)date('Y')
        );
    }
}
<?php

namespace App\Application\Feature\Account\RecoverPassword;

final readonly class RecoverPasswordMailBuilder
{
    public function buildSubject(): string
    {
        return 'Pixario · Zmień hasło';
    }

    public function buildHtml(string $recoverPasswordUrl): string
    {
        $safeRecoverPasswordUrl = htmlspecialchars(
            $recoverPasswordUrl,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        return sprintf(
            '
            <!DOCTYPE html>
            <html lang="pl">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Zmiana hasła Pixario</title>
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
                                            <span style="display:inline-block;padding:8px 14px;border-radius:999px;background:#eee7ff;color:#6f35e8;font-size:12px;font-weight:800;">
                                                Bezpieczeństwo konta
                                            </span>
                                        </div>

                                        <h1 style="margin:18px 0 0;font-size:38px;line-height:1.1;font-weight:800;letter-spacing:-0.04em;color:#ffffff;">
                                            Zmień swoje hasło
                                        </h1>

                                        <p style="margin:18px 0 0;max-width:520px;font-size:15px;line-height:1.7;color:rgba(255,255,255,0.82);">
                                            Otrzymaliśmy prośbę o zmianę hasła do Twojego konta Pixario.
                                            Kliknij poniższy przycisk, aby ustawić nowe hasło.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:36px;">

                                        <div style="padding:28px;border-radius:20px;background:#fbfaff;border:1px solid #eee8f7;text-align:center;">
                                            <div style="width:86px;height:86px;margin:0 auto 22px;border-radius:50%%;background:#eee7ff;color:#6f35e8;display:table;">
                                                <div style="display:table-cell;vertical-align:middle;text-align:center;font-size:38px;">
                                                    🔒
                                                </div>
                                            </div>

                                            <div style="font-size:12px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#6f35e8;">
                                                Reset hasła
                                            </div>

                                            <h2 style="margin:10px 0 0;font-size:28px;line-height:1.18;font-weight:800;letter-spacing:-0.03em;color:#17122b;">
                                                Ustaw nowe hasło
                                            </h2>

                                            <p style="margin:14px auto 0;max-width:440px;color:#7a728c;font-size:15px;line-height:1.7;">
                                                Link przeniesie Cię do bezpiecznej strony, gdzie możesz wprowadzić nowe hasło.
                                            </p>

                                            <a href="%s" style="display:inline-block;margin-top:28px;padding:15px 28px;border-radius:10px;background:linear-gradient(180deg,#8d55ff,#6228db);color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;box-shadow:0 12px 24px rgba(111,53,232,0.24);">
                                                Zmień hasło
                                            </a>
                                        </div>

                                        <div style="margin-top:28px;padding:18px 20px;border-radius:18px;background:#ffffff;border:1px solid #eee8f7;">
                                            <div style="font-size:14px;font-weight:800;color:#17122b;">
                                                Przycisk nie działa?
                                            </div>

                                            <p style="margin:8px 0 0;color:#7a728c;font-size:13px;line-height:1.6;">
                                                Skopiuj i wklej poniższy link w oknie przeglądarki:
                                            </p>

                                            <p style="margin:10px 0 0;word-break:break-all;color:#6f35e8;font-size:13px;line-height:1.6;">
                                                %s
                                            </p>
                                        </div>

                                        <div style="margin-top:28px;padding:18px 20px;border-radius:18px;background:#fff6df;border:1px solid #ffe4a3;color:#7a4e00;font-size:14px;line-height:1.6;">
                                            Jeśli to nie Ty prosiłeś o zmianę hasła, zignoruj tę wiadomość.
                                            Twoje aktualne hasło pozostanie bez zmian.
                                        </div>

                                        <div style="margin-top:36px;padding-top:24px;border-top:1px solid #eee8f7;font-size:13px;line-height:1.7;color:#8a839b;text-align:center;">
                                            Ten e-mail został wygenerowany automatycznie przez Pixario.<br>
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
            $safeRecoverPasswordUrl,
            $safeRecoverPasswordUrl,
            (int) date('Y')
        );
    }
}
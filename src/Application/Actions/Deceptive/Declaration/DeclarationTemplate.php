<?php
function makeTemplate($parsedData) {
    return <<<EOD
<style>
    
    .c {
        text-align: center;
    }
    
    .b {
        font-weight: bold;
        font-size: 12pt;
        margin: 8pt;
    }

    .j {
        text-align: justify;
    }
    
    .r {
        text-align: right;
        margin-top: 5pt;
        margin-bottom: 25pt;
    }
    
    .l {
        text-align: left;
    }
    
    .f {
        position: fixed;
    }

    p {
        font-size: 10pt;
        margin: 2pt;
    }
    
    body {
        text-align: left;
    }
    
</style>

<body>
    <p class="f" style="top:0;left:0;">Polskie Stowarzyszenie Klasy Open Skiff</p>
    <p class="f" style="top:0;right:0;">Miejscowość: <i>$parsedData->city</i>, <i>$parsedData->date</i></p>
    <br>
    <p style="margin-top: 2pt;">ul. Gen. J. Hallera 19 lok. 308</p>
    <p>84 – 120 Władysławo</p>
    
    <p class="c" style="font-size:10pt; color: #666; margin: 8pt 8pt 8pt 8pt;">Wypełnij elektronicznie edytowalne pola. Podpisz odręcznie miejsca „Podpis”. Wyślij deklarację na adres: Polskie&nbsp;Stowarzyszenie Klasy Open Bic ul. Gen. J. Hallera 19 lok. 308, 84 – 120 Władysławowo. Dziękujemy.</p>

    <p class="c b">Deklaracja Członkowska Polskiego Stowarzyszenia Klasy Open Skiff.</p>
    <p>Imię i Nazwisko: <i>$parsedData->name $parsedData->surname</i>.</p>
    <p>Data urodzenia: <i>$parsedData->birthdate</i>.</p>
    <p>PESEL: <i>$parsedData->pesel</i>.</p>
    <p>Adres zamieszkania: <i>$parsedData->road</i>, <i>$parsedData->addr1</i>/<i>$parsedData->addr2</i>, <i>$parsedData->postal</i>, <i>$parsedData->city</i>.</p>
    <p>Numer telefonu: <i>$parsedData->phone1</i>.</p>
    <p>E-mail: <i>$parsedData->email1</i>.</p>
    <p>Klub: <i>$parsedData->club</i>.</p>
    <p>Funkcja (trener, zawodnik, rodzic, działacz): <i>$parsedData->function</i>.</p>
    <p>Proszę o przyjęcie mnie w poczet członków Polskiego Stowarzyszenia Klasy Open Skiff, zapoznałem się i zobowiązuję się doprzestrzegania postanowień Statutu PSKOS</p>
    <p class="r">Podpis <span style="color: #666;">………………………………</span></p>
    <p class="c b">Dotyczy niepełnoletnich. Wypełnia opiekun prawny.</p>
    <p>Wyrażam zgodę na członkostwo i aktywny udział: <i>$parsedData->name $parsedData->surname</i> w Polskim Stowarzyszeniu Klasy Open Skiff</p>
    <p>Imię i nazwisko opiekuna prawnego (rodzica): <i>$parsedData->name2 $parsedData->surname2</i>.</p>
    <p>Numer telefonu opiekuna prawnego: <i>$parsedData->phone2</i>, e–mail: <i>$parsedData->email2</i>.</p>
    <p class="c b">Dotyczy pełnoletnich członków PSKOS oraz opiekunów prawnych nieletnich członków PSKOS.</p>
    <p>Wyrażam zgodę na przesyłanie informacji drogą elektroniczną.</p>
    <p class="r">Podpis <span style="color: #666;">………………………………</span></p>
    <p>Wyrażam zgodę na przechowywanie i przetwarzanie moich danych osobowych przez Polskie Stowarzyszenie Klasy Open Skiff (ul. Gen. J. Hallera 19, 84 – 120 Władysławowo) w celach związanych z działalnością statutową PSKOB. Zgodnie zustawą o ochronie danych osobowych przysługuje mi prawo wglądu do treści swoich danych, aktualizowania oraz ichpoprawiania. Podstawa prawna: ustawa z dnia 29 sierpnia 1997 r. o ochronie danych osobowych (Dz. U. z 2002 roku, Nr101, poz. 926 z późn. zm.)</p>
    <p class="r">Podpis <span style="color: #666;">………………………………</span></p>
    <p>Adnotacje Zarządu PSKOS:</p>
    <p class="c" style="color: #666;">…………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………</p>
    <p class="f" style="bottom:0; left: 0; font-size:10pt; color: #666;">Numer ewidencyjny: <code>$parsedData->id</code></p>
</body>

EOD;
}
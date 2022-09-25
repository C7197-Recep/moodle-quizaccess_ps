<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * privacy provider file
 * @package    quizaccess_ps
 * @category   quiz
 * @copyright  2022 ProctorStone <cto@proctorstone.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// General
$string['pluginname'] = 'ProctorStone';
$string['preflight_information'] = '<strong>Sınava başlamak için web kameranız ve mikrofonunuzun olması, ve bunların kullanımına izin vermeniz gerekmektedir. Sınav başlangıcında kimlik ve/veya portre fotoğrafınızı çekmeniz gerekecektir. Kurumunuzun seçimine göre sınav esnasında periyodik olarak fotoğrafınız çekilebilir, video kaydınız ve/veya ekran kaydınız alınabilir.</strong>';

// Report
$string['report:title'] = 'ProctorStone Proctoring';

// Settings
$string['setting:app_key'] = 'Application Key';
$string['setting:app_key_desc'] = 'Proctorstone tarafından size verilen, ya da kullanıcı panelinizden aldığınız uygulama anahtarını giriniz.';
$string['setting:app_secret'] = 'Application Secret';
$string['setting:app_secret_desc'] = 'Proctorstone tarafından size verilen, ya da kullanıcı panelinizden aldığınız uygulama şifresini giriniz.';

// Privacy API 
$string['privacy:metadata:quizaccess_ps:proctoring_api:user_id'] = 'Öğrencinin Moodle kullanıcı kimliğini saklamak için user_id parametresini kullanıyoruz. Bu, hangi gözetmenlik raporunun hangi öğrenciye ait olduğundan emin olmaya yardımcı olur.';
$string['privacy:metadata:quizaccess_ps:proctoring_api:firstname'] = 'Öğrencinin adını veri tabanımızda saklamak için firstname parametresini kullanıyoruz. Bu bilgi, öğretmenlerin öğrenciyi tanımlayabilmesi içindir.';
$string['privacy:metadata:quizaccess_ps:proctoring_api:lastname'] = 'Öğrencinin soyadını veri tabanımızda saklamak için lastname paremetresini kullanıyoruz. Bu, öğretmenlerin öğrenciyi tanımlayabilmesi içindir.';
$string['privacy:metadata:quizaccess_ps:proctoring_api'] = 'Proctoring Api\'mizi, öğrencinin yeni sekmeler açma, geliştirici konsolunu açma veya ikinci monitör takma gibi herhangi bir yasak eylemi yapmaya çalışıp çalışmadığını belirlemek için kullanırız.';

// For Later
// $string['proctoringrequired'] = 'ProctoreStone';
// $string['notrequired'] = 'Zorunlu Değil';
// $string['proctoringrequired_help'] = 'Eğer bu seçeneği aktive ederseniz, öğrenciler web kamerasıyla ilgili koşulları onayladığını belirten bir kutucuğu işaretlemeden sınavı başlatamazlar.';
// $string['proctoringrequiredoption0'] = 'ProctorStone-Starter';
// $string['proctoringrequiredoption1'] = 'ProctorStone-Level1';
// $string['proctoringrequiredoption2'] = 'ProctorStone-Level2';
// $string['proctoringrequiredoption3'] = 'ProctorStone-Level3';
// $string['proctoringrequiredoption4'] = 'ProctorStone-Level4';
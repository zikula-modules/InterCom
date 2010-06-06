<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 * License
 * -------
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @author InterCom development team
 * @link http://code.zikula.org/intercom/ Support and documentation
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 *
 */

// neu
define('_IC_FORCEEMAILNOTIFICATION', 'E-Mailbenachrichtigung bei neuen Benutzern erzwingen');
define('_IC_FORCEEMAILNOTIFICATION_HINT', 'Um die E-Mailbenachrichtigung bei neuen Benutzern zu ermöglichen muss der InterCom-Hook für das Users-Module aktiviert sein. Dies schaltet auch die Willkommensnachricht für neue User ein, siehe unten.');

//
// A
//
define('_IC_ADDBUDDY', 'Kontakt hinzufügen');
define('_IC_ADDED', 'hinzugefügt');
define('_IC_ADMINISTRATION', 'Administration');
define('_IC_ADMIN_FUNCTIONS', 'Admin Funktionen');
define('_IC_AIM', 'AIM');
define('_IC_ALLOW_AUTOREPLY', 'Automatische Antwort erlauben?');
define('_IC_ALLOW_AUTOREPLY_HINT', 'Mit der Funktion \'Automatische Antwort\' können Benutzer eine Nachricht erstellen, die automatisch als Antwort auf alle eingehenden Nachrichten gesendet wird.');
define('_IC_ALLOW_EMAILNOTIFICATION', 'E-Mail Benachrichtigung erlauben?');
define('_IC_ALLOW_HTML', 'HTML erlauben?');
define('_IC_AMOUNT', 'Anzahl');
define('_IC_ANSWERED', 'beantwortet');
define('_IC_ARCHIVE', 'Archiv');
define('_IC_ARCHIVINGMESSAGE', 'Nachricht wird archiviert');
define('_IC_ARCHIVELIMITREACHED', 'Es sind zuviele Nachrichten im Archiv. Bitte überflüssige Nachrichten im Archiv löschen, um dort wieder Nachrichten speichern zu können.');
define('_IC_AUTOREPLY', 'Automatische Antwort');
define('_IC_AUTOREPLYON', 'Die Funktion "Automatische Antwort" ist aktiviert.');
define('_IC_AUTOREPLY_DESC', 'Soll beim Empfang einer neuen privaten Nachricht eine automatische Antwort verschickt werden?');
define('_IC_AUTOREPLY_TEXT', 'Inhalt der automatischen Antwort');

//
// B
//
define('_IC_BACK_TO_TOP', 'nach oben');
define('_IC_BLOCKED', 'Der Empfänger verweigert die Annahme für diesen Absender. Die Nachricht wurde nicht übermittelt.');
define('_IC_BLOCKUNAME', 'Benutzernamen anzeigen?');

//
// C
//
define('_IC_CANCEL', 'Abbruch');
define('_IC_CANCELFORWARD', 'Weiterleitung abbrechen');
define('_IC_CANCELREPLY', 'Antwort abbrechen');
define('_IC_CANCELSEND', 'Private Nachricht nicht senden');
define('_IC_CHECKALL', 'Alle markieren');
define('_IC_CHECKANDTRY', 'Bitte überprüfen und nochmal versuchen.');
define('_IC_CHECKDESTINATIONANDTRY', 'Bitte die Zieladresse prüfen und bei Bedarf korrigieren. Dann nochmal versuchen.');
define('_IC_CHECKICONANDTRY', 'Bitte das Symbol überprüfen und nochmal versuchen.');
define('_IC_CHECKMESSAGEANDTRY', 'Etwas stimmt mit der Nachricht nicht. Bitte prüfen, ändern und nochmal versuchen.');
define('_IC_CHECKNAMEANDTRY', 'Ungültiger Benutzername. Er muss im System bekannt sein.');
define('_IC_CHECKSUBJECTANDTRY', 'Mit dem Betreff stimmt etwas nicht, bitte prüfen, ändern und nochmal versuchen.');
define('_IC_CLOSEWINDOW', 'Fenster schließen');
define('_IC_CONFIGUPDATED', 'Neue Konfiguration gespeichert');
define('_IC_COULDNOTREGISTERCREATEHOOK', 'Fehler beim Anlegen des create hook');
define('_IC_CREATEINDEXFAILED',      'Das Anlegen des Index schlug fehl.');
define('_IC_CREATETABLEFAILED',      'Das Erstellen einer Tabelle schlug fehl.');
define('_IC_CURRENT_MESSAGES', 'Momentane Nachrichtenanzahl in');

//
// D
//
define('_IC_DATE', 'Datum');
define('_IC_DELETE', 'Löschen');
define('_IC_DELETEMESSAGEFAILED', 'Das Löschen der Nachricht schlug fehl.');
define('_IC_DELETESELECTED', 'Ausgewählte Mails löschen');
define('_IC_DELETE_ALL_ARCHIVES', 'Alle Archive leeren');
define('_IC_DELETE_ALL_ARCHIVES_FAILED', 'Leerung aller Archive fehlgeschlagen');
define('_IC_DELETE_ALL_ARCHIVES_SUCCEEDED', 'Alle Archive geleert');
define('_IC_DELETE_ALL_INBOXES', 'Alle Posteingänge leeren');
define('_IC_DELETE_ALL_INBOXES_FAILED', 'Leerung aller Posteingänge fehlgeschlagen');
define('_IC_DELETE_ALL_INBOXES_SUCCEEDED', 'Alle Posteingänge erfolgreich geleert');
define('_IC_DELETE_ALL_MESSAGES', 'Alle Nachrichten löschen');
define('_IC_DELETE_ALL_MESSAGES_FAILED', 'Löschung aller Nachrichten fehlgeschlagen');
define('_IC_DELETE_ALL_MESSAGES_SUCCEEDED', 'Alle Nachrichten gelöscht');
define('_IC_DELETE_ALL_OUTBOXES', 'Alle Postausgänge leeren');
define('_IC_DELETE_ALL_OUTBOXES_FAILED', 'Leerung aller Posteingänge fehlgeschlagen');
define('_IC_DELETE_ALL_OUTBOXES_SUCCEEDED', 'Alle Postausgänge erfolgreich geleert');
define('_IC_DELETE_CONTINUE',        'Löschvorgang fortsetzen');
define('_IC_DELETE_KEEPDB',          'Tabellen NICHT aus der Datenbank löschen');
define('_IC_DELETE_MODULE',          'Deinstallation InterCom');
define('_IC_DELETE_REMOVEDB',        'Tabellen aus der Datenbank LÖSCHEN');
define('_IC_DELETE_STEP1',           'Erster Schritt des Löschvorganges');
define('_IC_DELETE_STEP2',           'Zweiter Schritt des Löschvorganges');
define('_IC_DELETE_STEP3',           'Letzter Schritt des Löschvorganges');
define('_IC_DELETE_THANKS',          'Vielen Dank für die Benutzung von InterCom.');
define('_IC_DELETINGMESSAGE',        'Nachricht wird gelöscht...');
define('_IC_DISABLE_HTML_ONTHISPOST', 'HTML ausschalten');
define('_IC_DISPLAY_USER_MESSAGE', 'Usernachricht anzeigen?');
define('_IC_DONOTTRYTHISAGAIN', 'Versuch das nicht noch einmal...');
define('_IC_DONTHAVEMESSAGES', 'Keine Nachrichten');

//
// E
//
define('_IC_EMAIL', 'E-Mail');
define('_IC_EMAILNOTIFICATION', 'E-Mail-Benachrichtigung');
define('_IC_EMAILNOTIFICATION_DESC', 'Soll beim Empfang einer neuen privaten Nachricht eine E-Mail-Benachrichtigung verschickt werden?');
define('_IC_ERR', 'Fehler');
define('_IC_EXAMPLE', 'Beispiele');
define('_IC_EXPORT', 'exportieren');
define('_IC_EXTRAINFO', 'Extrainfo');

//
// F
//
define('_IC_FAILEDTOUPGRADEHOOK', 'Fehler beim Hookupgrade');
define('_IC_FORWARD', 'Weiterleiten');
define('_IC_FROM', 'Absender');
define('_IC_FW', 'FW');

//
// G
//
define('_IC_GENERAL_CONFIG', 'Generell');
define('_IC_GOTOINBOX', 'Private Nachrichten lesen');

//
// H
//
define('_IC_HANDLE_WITH_CARE', 'NUR MIT VORSICHT BENUTZEN!!'); // http://www.lyricsfreak.com/t/travelling+wilburys/handle+with+care_20140463.html
define('_IC_HINT_MULTIPLEGROUPS', 'Bitte den Namen der Gruppe eingeben.');
define('_IC_HINT_MULTIPLERECIPIENTS', 'Bitte den Namen des Benutzers eingeben.');

//
// I
//
define('_IC_ICQ', 'ICQ');
define('_IC_IMPORT',                 'Import');
define('_IC_IMPORT_CORE',            'Nachrichten aus dem Messages Modul (Core) importieren');
define('_IC_IMPORT_CORE_FAILED',     'Import der Nachrichten aus dem Messages Modul (Core) fehlgeschlagen');
define('_IC_IMPORT_CORE_SUCCEEDED',  'Import der Nachrichten aus dem Messages Modul (Core) erfolgreich');
define('_IC_IMPORT_NO',              'Keine Nachrichten importieren');
define('_IC_INBOX', 'Posteingang');
define('_IC_INBOXTSUMMARY', 'Nachrichten im Posteingang');
define('_IC_INBOX_SHORT', 'Post');
define('_IC_INIT_ACTIVATE',          'InterCom-Modul aktivieren');
define('_IC_INIT_CONTINUE',          'Installation beginnen');
define('_IC_INIT_MODULE',            'InterCom Installation');
define('_IC_INIT_STEP1',             'Schritt 1 - Willkommen bei der Installation von InterCom');
define('_IC_INIT_STEP2',             'Schritt 2 - Import');
define('_IC_INIT_STEPFINAL',         'Abschluß');
define('_IC_INIT_THANKS',            'Vielen Dank für Ihre Geduld und viel Spaß mit InterCom');
define('_IC_INIT_WELCOME',           'Herzlich Willkommen bei der Installation von InterCom - dem Instant-Messenger für Zikula');
define('_IC_INTERESTS', 'Interessen');
define('_IC_ISNOTINDB', 'ist nicht in der Datenbank.');
define('_IC_IS_IC_ACTIVE', 'Ist InterCom aktiviert?');

//
// L
//
define('_IC_LAST', 'Die neuesten');
define('_IC_LAST_ONLINE', 'zuletzt online');
define('_IC_LIMITREACHED1', 'Bitte einige Nachrichten aus dem Posteingang löschen. Das Limit wurde erreicht.');
define('_IC_LIMITREACHED2', 'Neue Nachrichten werden erst angezeigt, nachdem alte gelöscht worden sind.');
define('_IC_LIMIT_ARCHIVE', 'Limit Archiv');
define('_IC_LIMIT_INBOX', 'Limit Posteingang');
define('_IC_LIMIT_OUTBOX', 'Limit Postausgang');
define('_IC_LOADINGFORWARD', 'Weiterleiten wird vorbereitet, bitte einen Augenblick warten...');
define('_IC_LOADINGREPLY', 'Antworten wird vorbereitet, bitte einen Augenblick warten...');
define('_IC_LOCATION', 'Wohnort');
define('_IC_LOGIN', 'Anmeldung');
define('_IC_LOGINFAILED', 'Login ist fehlgeschlagen!');
define('_IC_LOGINNEEDED', 'Für den Aufruf dieser Seite ist eine Anmeldung notwendig.');

//
// M
//
define('_IC_MAIL_CONFIG', 'E-Mail Benachrichtigung');
define('_IC_MAIL_FROM', 'E-Mail Absender');
define('_IC_MAIL_FROM_DESC', 'Bleibt das Feld "Absender" leer, so wird automatisch der Seitenname eingesetzt.');
define('_IC_MAIL_FROM_EMAIL', 'E-Mail Absenderadresse');
define('_IC_MAIL_FROM_EMAIL_DESC', 'Bleibt das Feld "Absenderadresse" leer, so wird automatisch die E-Mailadresse des Administrators eingesetzt.');
define('_IC_MAIL_SUBJECT', 'E-Mail-Betreff');
define('_IC_MAINTENANCE', 'Wartungseinstellungen');
define('_IC_MAINTENANCE_MESSAGE', 'Hinweistext');
define('_IC_MAIN_CONFIG', 'Konfiguration');
define('_IC_MEMBERLIST', 'Mitgliederliste');
define('_IC_MESSAGEARCHIVED', 'Die Nachricht wurde ins Archiv verschoben');
define('_IC_MESSAGEDOESNOTEXIST', 'Diese Nachricht existiert nicht.');
define('_IC_MESSAGEHASTOBETHERECIEPIENT', 'Nur Empfänger und Absender können die private Nachricht lesen.');
define('_IC_MESSAGEID', 'Nachrichten ID');
define('_IC_MESSAGES', 'Nachrichten');
define('_IC_MESSAGESPERPAGE', 'Anzahl Nachrichten pro Seite');
define('_IC_MESSAGESSETTINGS', 'Private Nachrichten konfigurieren');
define('_IC_MISSINGARCHIVELIMIT', 'Fehlendes Limit für das Archiv');
define('_IC_MISSINGINBOXLIMIT', 'Fehlendes Limit für den Posteingang');
define('_IC_MISSINGMAINTENANCENOTICE', 'Fehlender Hinweis für Wartungsarbeiten o.ä.');
define('_IC_MISSINGOUTBOXLIMIT', 'Fehlendes Limit für den Postausgang');
define('_IC_MISSINGPERPAGE', 'Fehlende Angabe für die Nachrichtenzahl pro Seite');
define('_IC_MISSINGSUBJECT', 'Fehlender Betreff für Benachrichtigungsmail');
define('_IC_MISSINGUSERPROMPT', 'Fehlende Userinformation');
define('_IC_MISSINGWELCOMEMESSAGE', 'Willkommensnachricht fehlt');
define('_IC_MISSINGWELCOMEMESSAGESENDER', 'Absendername für Willkommensnachricht fehlt');
define('_IC_MISSINGWELCOMEMESSAGESUBJECT', 'Betrefftext für Willkommensnachricht fehlt');
define('_IC_MODAL_CLOSEMODAL', 'Fenster schließen');
define('_IC_MODAL_NEWMESSAGES', 'neue Nachricht/en');
define('_IC_MODAL_NEWMESSAGESREAD', 'Jetzt lesen');
define('_IC_MODAL_YOUHAVE', 'Sie haben');
define('_IC_MODIFYCONFIG',  'Konfiguration ändern');
define('_IC_MSGDELETED', 'Die Nachricht wurde gelöscht.');
define('_IC_MSGPOSTED', 'Die Nachricht wurde übermittelt');
define('_IC_MSGSPOSTED', '%msgs% Nachrichten wurden erfolgreich übermittelt.');
define('_IC_MSGSTATUS', 'Status der Nachricht');
define('_IC_MSNM', 'MSNM');
define('_IC_MYEMAIL', 'E-Mail');
define('_IC_MYHOMEPAGE', 'Homepage');

//
// N
//
define('_IC_NEWMESSAGES', 'Neue private Nachrichten');
define('_IC_NEWPM', 'Neue Nachricht');
define('_IC_NEWPM_SHORT', 'Neue Mail');
define('_IC_NEWPRIVATEMESSAGE', 'Es gibt neue Nachrichten!');
define('_IC_NEWPRIVATEMSG', 'Neue private Nachrichten');
define('_IC_NEXTMSG', 'Nächste Nachricht');
define('_IC_NICKNAME', 'Benutzername');
define('_IC_NOAUTH', 'Keine Authorisierung');
define('_IC_NOBUDDIESINLIST', 'Keine Buddies in der Liste');
define('_IC_NODESTINATION', 'Es wurde kein Empfänger eingegeben.');
define('_IC_NOHTMLALLOWED', 'Kein HTML erlaubt');
define('_IC_NOICON', 'Kein Symbol angegeben.');
define('_IC_NOMESSAGE', 'Fehler: kein Nachrichtentext eingegeben');
define('_IC_NORECIPIENTFOUND', 'Fehler: keinen Empfänger gefunden');
define('_IC_NOSUBJECT', 'Fehler: keinen Betreff angegeben');
define('_IC_NOTIFYBODY1', 'Neue private Nachricht von Benutzer');
define('_IC_NOTIFYBODY2', 'erhalten.');
define('_IC_NOTIFYBODY3', 'Nachrichten lesen mit Klick auf den Link');
define('_IC_NOTIFYBODY4', 'Hier Einstellungen für Benachrichtigungen ändern');

//
// O
//
define('_IC_OCCUPATION', 'Beschäftigung');
define('_IC_OFFLINE', 'offline');
define('_IC_ONLINE', 'online');
define('_IC_OPTIMIZE_TABLE', 'Optimiere Tabelle');
define('_IC_OPTIMIZE_TABLE_DESC', 'Dies entfernt alle Nachrichten aus der Datenbank, die sich keinem Postfach zuordnen lassen.');
define('_IC_OPTIMIZE_TABLE_FAILED', 'Optimierung der Tabelle fehlgeschlagen');
define('_IC_OPTIMIZE_TABLE_SUCCEEDED', 'Optimierung der Tabelle erfolgreich');
define('_IC_OUTBOX', 'Postausgang');
define('_IC_OUTBOXLIMITREACHED', 'Es sind zuviele Nachrichten im Postausgang. Bitte überflüssige Nachrichten im Postausgang löschen, um wieder Nachrichten versenden zu können.');

//
// P
//
define('_IC_PASSWORD', 'Kennwort');
define('_IC_PM', 'PN');
define('_IC_PMUNAVAILABLE', 'Das private Mitteilungssystem steht aktuell nicht zur Verfügung!');
define('_IC_PNTEMPLATE', 'Name der Templatedatei');
define('_IC_PREVIEW', 'Vorschau');
define('_IC_PREVIEWPM', 'Vorschau der Nachricht anzeigen');
define('_IC_PREVIOUSMSG', 'Vorherige Nachricht');
define('_IC_PRINT', 'Drucken');
define('_IC_PRINTMESSAGE', 'Nachricht drucken');
define('_IC_PRINTPAGE', '[ -- Diese Seite drucken -- ]');
define('_IC_PROFIL', 'Profil');
define('_IC_PROFILELINK', 'Benutzername mit Link zum Profil?');
define('_IC_PROTECTION', 'Spamschutz');
define('_IC_PROTECTION_ALERT', 'Spamschutz Alarm');
define('_IC_PROTECTION_ALERT_THEUSER', 'Der Benutzer');
define('_IC_PROTECTION_ALERT_VIOLATE', 'hat versucht, Nachrichten an eine Vielzahl von Personen innerhalb sehr kurzer Zeit zu senden. Der Spamschutz hat dies verhindert.');
define('_IC_PROTECTION_AMOUNT', 'Anzahl der Nachrichten');
define('_IC_PROTECTION_HINT', 'Der Spamschutz legt fest, wieviele Nachrichten jeder User in einer bestimmten Zeit (= Dauer des Schutzes) versenden darf. Dabei wird bei Nachrichten an mehrere Empfänger jeder Empfänger als einzelne Nachricht gezählt.');
define('_IC_PROTECTION_MAIL', 'Verstöße gegen den Spamschutz mailen?');
define('_IC_PROTECTION_ON', 'Spamschutz einschalten?');
define('_IC_PROTECTION_TIME', 'Dauer des Schutzes (in Minuten)');

//
// R
//
define('_IC_RE', 'Re');
define('_IC_READ', 'gelesen');
define('_IC_READMSG', 'Nachricht lesen');
define('_IC_READ_OUTBOX', 'vom Empfänger bereits gelesen');
define('_IC_REALLY_DELETE_ALL_ARCHIVES', 'Wirklich ALLE privaten Nachrichten, die sich in den Archiven befinden, unwiederbringlich aus der Datenbank löschen?');
define('_IC_REALLY_DELETE_ALL_INBOXES', 'Wirklich ALLE privaten Nachrichten, die sich in den Posteingängen, befinden unwiederbringlich aus der Datenbank löschen?');
define('_IC_REALLY_DELETE_ALL_MESSAGES', 'Woirklich ALLE privaten Nachrichten unwiederbringlich aus der Datenbank löschen?');
define('_IC_REALLY_DELETE_ALL_OUTBOXES', 'Wirklich ALLE privaten Nachrichten, die sich in den Postausgängen befinden, unwiederbringlich aus der Datenbank löschen?');
define('_IC_REALLY_OPTIMIZE_TABLE', 'Wirklich die InterCom Tabelle optimieren?');
define('_IC_REALLY_RESTORE_DEFAULTS', 'Wirklich die InterCom Einstellungen auf Grundeinstellungen zurücksetzten?');
define('_IC_REMEMBERME', 'Login speichern');
define('_IC_REPLY', 'Antworten');
define('_IC_RESTORE_DEFAULTS', 'Einstellungen zurücksetzen!');
define('_IC_RESTORE_DEFAULTS_DESC', 'Dies setzt alle Einstellung auf Standardwerte zurück.');
define('_IC_RESTORE_DEFAULTS_FAILED', 'Zurücksetzen der Einstellung fehlgeschlagen');
define('_IC_RESTORE_DEFAULTS_SUCCEEDED', 'Einstellung erfolgreich zurückgesetzt');

//
// S
//
define('_IC_SAVE', 'Speichern');
define('_IC_SAVESELECTED', 'Ausgewählte Mails speichern');
define('_IC_SAVESETTINGS', 'Einstellungen speichern');
define('_IC_SAVEWELCOMEMESSAGE', 'Willkommensnachricht im Postausgang speichern');
define('_IC_SEND', 'Absenden');
define('_IC_SENDFORWARD', 'Nachricht weiterleiten');
define('_IC_SENDMESSAGEFAILED', 'Das Senden der Nachricht schlug fehl.');
define('_IC_SENDPM', 'Private Nachricht senden');
define('_IC_SENDREPLY', 'Antwort senden');
define('_IC_SENDWELCOMEMESSAGE', 'Nachricht an neue Benutzer senden?');
define('_IC_SENT', 'Vom');
define('_IC_SHOWDATE', 'Datum der PM anzeigen?');
define('_IC_SIGNATURE', 'Signatur');
define('_IC_SORTEDBY', 'sortiert nach:');
define('_IC_START',  'Start');
define('_IC_STATUS', 'Status');
define('_IC_STOREMESSAGEFAILED', 'Das Speichern der Nachricht schlug fehl.');
define('_IC_STORINGFORWARD', 'Nachricht wird weitergeleitet, bitte einen Augenblick warten...');
define('_IC_STORINGREPLY', 'Antwort wird gespeichert, bitte einen Augenblick warten...');
define('_IC_SUBJECT', 'Betreff');

//
// T
//
define('_IC_TEMPLATE_HINT', 'Folgende Optionen können abhängig vom Template aktiviert werden');
define('_IC_TEXT', 'Text');
define('_IC_THEUSER', 'Der Benutzer');
define('_IC_TIMEASC', 'Zeit (Aufsteigend)');
define('_IC_TIMEDESC', 'Zeit (Absteigend)');
define('_IC_TITLE', 'Private Nachrichten');
define('_IC_TO', 'Empfänger');
define('_IC_TOOLS', 'Werkzeuge');
define('_IC_TOOMANYMESSAGES', 'Spamschutz Fehler: Sie haben versucht zuviele Benutzer in zu kurzer Zeit anzuschreiben!');
define('_IC_TOPROFILOF', 'Zum Benutzerprofil von');
define('_IC_TOTAL', 'total');
define('_IC_TO_GROUP', 'Nachricht an Gruppe');
define('_IC_TURNEDOFF', 'Dieses Feature wurde vom Administrator ausgeschaltet.');

//
// U
//
define('_IC_UNKNOWNGROUP', 'Unbekannte Gruppe: ');
define('_IC_UNKNOWNUSER', 'Unbekannter Benutzer: ');
define('_IC_UNREAD', 'ungelesen');
define('_IC_UNREAD_OUTBOX', 'vom Empfänger noch nicht gelesen');
define('_IC_UPDATETABLEFAILED',      'Das Aktualisieren der Tabelle schlug fehl.');
define('_IC_USEBBCODE', 'BBCode');
define('_IC_USERDELETE', 'Benutzer löschen');
define('_IC_USERDELETED', '*Gelöschter Benutzer*');
define('_IC_USERDELETEDNOREPLY', 'Diesem Benutzer kann nicht geantwortet werden, da er gelöscht wurde.');
define('_IC_USEREDIT', 'Benutzer editieren');
define('_IC_USEREMAILNOTIFIED', 'Empfänger per E-Mail benachrichtigen');
define('_IC_USERNAME', 'Name');
define('_IC_USERPREFS', 'Einstellungen');
define('_IC_USERPROMPT', 'Willkommen bei InterCom!');
define('_IC_USERSTATUS', 'Userstatus');
define('_IC_USER_INFO', 'Benutzerinformationen');
define('_IC_USER_MESSAGE', 'Nachricht an Mitglieder');
define('_IC_USER_MESSAGE_INFO', 'Diese Nachricht wird oberhalb des Posteingangs angezeigt. Hier können z.b. die User über Löschungen informiert werden.');

//
// W
//
define('_IC_WEBSITE', 'Website');
define('_IC_WELCOMEMESSAGEFORTHISLANGUAGE', 'Willkommensnachricht für die aktuelle Sprache');
define('_IC_WELCOMEMESSAGESENDERDOESNOTEXIST', 'unbekannter Benutzer');
define('_IC_WELCOMEMESSAGE_CONFIG', 'Willkommensnachricht');
define('_IC_WELCOME_MESSAGE', 'Text der Willkommensnachricht');
define('_IC_WELCOME_MESSAGESENDER', 'Absender der Willkommensnachricht');
define('_IC_WELCOME_MESSAGESENDER_HINT', 'Der Absendername muss als Benutzer im System bekannt sein.');
define('_IC_WELCOME_MESSAGESUBJECT', 'Betreff der Willkommensnachricht');
define('_IC_WELCOME_MESSAGE_HINT', 'Folgende Platzhalter werden unterstützt:<ul><li>%username% für den Benuternamen</li><li>%realname% für den echten Namen des Benutzers</li><li>%sitename% für den Namen dieser Webseite</li></ul>Fängt der Text mit einem _ an, wird er wie eine Sprachkonstante behandelt. Diese Konstante sollte in /modules/InterCom/pnlang/xxx/welcome.php gespeichert werden (xxx = Sprachkürzel).');
define('_IC_WROTE', 'schrieb');

//
// Y
//
define('_IC_YIM', 'YIM');

{!! MailVariable::prepareData(str_replace('{{ reset_link }}', $link, '

{{ header }}

<strong>안녕하세요. 이화이언입니다.</strong> <br /><br />

비밀번호를 재설정을 위한 메일을 보내드립니다.<br/>
아래의 링크를 클릭하여 새로운 비밀번호를 설정해주시기 바랍니다. <br /><br />

<a href="{{ reset_link }}">Reset password</a> <br /><br />

만약 새로운 비밀번호 설정을 요청하신 적이 없다면 이 메일을 무시해주시면 됩니다.<br/>
감사합니다.<br/>
(본 메일은 발송전용 메일입니다.<br/>
문의가 있는 분들은 www.ewhaiancom@daum.net으로 연락주시기 바랍니다.)"<br/>

{{ footer }}

')) !!}

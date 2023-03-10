{!! MailVariable::prepareData(str_replace('{{ reset_link }}', $data, '

{{ header }}

<strong>안녕하세요. 이화이언입니다.</strong> <br />

찾으신 이화이언 아이디를 다음과 같이 알려드립니다.<br/>

{{reset_link}}

만약 아이디 찾기를 요청하신 적이 없다면 이 메일을 무시해주시면 됩니다.<br/>
감사합니다.<br/>
(본 메일은 발송전용 메일입니다.<br/>
문의가 있는 분들은 www.ewhaiancom@daum.net으로 연락주시기 바랍니다.)"<br/>

{{ footer }}

')) !!}

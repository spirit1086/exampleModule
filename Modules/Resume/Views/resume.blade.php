<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{env('APP_NAME')}}</title>
    <style>
        html,body {
            font-family: 'DejaVu Sans';
            padding: 0;
            background-color: white;
            margin: 20px;
            font-size: 16px;
            font-weight: 400;
            line-height: 16px;
        }
        .info__txt {
            color: #adadad;
        }

        section{
            clear: both;
            font-size: 14px;
            font-weight: normal;
        }
    </style>
</head>
<body>
      <header style="width: 810px; height: 60px;background-color: rgb(233 233 233);position: absolute;top:-30px;left:-20px;right: 0">
          <img style="margin: 15px;float: right;" height="40px" src="data:image/png;base64,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" />
      </header>

      <main style="width: 100%">
        <section style="padding-bottom: 24px;">
            <div style="width: 150px;  padding-top: 8px; margin-right: 10px; float: left">
                <img width="150px;" src="{{$resume->avatar}}">
            </div>

            <div style="float: left; padding-top: 12px;">
                        <span style="font-size: 26px;">
                            {{$resume->fio}}
                        </span>
                <br />
                <span>{{$resume->gender}}, {{$resume->age}}, {{$resume->birthday_string}}</span>
                <br />
                <span>{{$resume->mobile}}</span>
                <br />
                <span>{{$resume->email}}</span>
                <br/>
                <span>Ожидаемый уровень заработной платы</span>
                <span class="info__txt"> - {{$resume->salary_level ?? 'не указано'}}</span>
                <br />
                <span>Проживает: {{$resume->city}}</span>
                <br />
                <span>Гражданство: {{$resume->citizenship}}</span>
                <br />
                <span>{{$resume->bussinnesTrip}}</span>
            </div>
        </section>

        <section style="padding-bottom: 24px;" >
            <div style="padding-bottom: 10px;margin-top: 20px">
                        <span class="info__txt" style="font-size: 20px;">
                            Опыт работы — {{$resume->experience}}
                        </span>
                <div style="width: 100%; height: 2px; background-color: #adadad;"></div>
            </div>

            @foreach($resume->jobs as $job)
            <div style="width: 100%;">
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px; font-size: 14px">
                    {{$job->dates}}
                </div>
                <div style="float: left;">
                    <span style="font-size: 16px; font-weight: bold; padding-bottom: 5px;">
                       {{$job->company}}
                    </span>
                    <br />
                    <span class="info__txt" style="padding-bottom: 10px;">
                      {{$job->country}}, {{$job->city}}
                    </span>
                    <br />
                    <span style="font-size: 16px; padding:25px 0 15px;">{{$job->position}}</span>
                    <br />
                    <span>{{$job->official_duties}} </span>
                </div>
                <div style="width: 100%;clear: both"></div><br/>
            </div>
            @endforeach
        </section>

        <section style="padding-bottom: 24px;" >
            <div style="padding-bottom: 10px;margin-top: 20px">
              <span class="info__txt" style="font-size: 24px;">Образование</span>
              <div style="width: 100%; height: 2px; background-color: #adadad;"></div>
            </div>
            @foreach ($resume->educations as $education)
                <div style="display: block;clear: both">
                    <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                        {{$education->dates}}
                    </div>
                    <div style="float: left;width:500px;">
                        <div class="info__txt" style="font-size: 14px;margin-bottom: 5px">{{$education->document}}</div>
                        <div style="font-size: 18px; font-weight: bold; padding-bottom: 5px;">
                          {{$education->edu_title}}
                        </div>
                        @if($education->specialization)
                        <div class="info__txt" style="font-size: 14px;margin: 5px 0">Специализация</div>
                        <div style="padding-bottom: 10px;width:500px;">
                           {{$education->specialization}}
                        </div>
                        @endif
                        @if($education->academic_degree)
                        <div class="info__txt" style="font-size: 14px;margin: 5px 0">Ученая степень</div>
                        <div style="padding-bottom: 10px;width:500px;">
                            {{$education->academic_degree}}
                        </div>
                        @endif
                        @if($education->publications)
                        <div class="info__txt" style="font-size: 14px;margin: 5px 0">Публикации</div>
                        <div style="padding-bottom: 10px;width:500px;">
                            {{$education->publications}}
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </section>

        <section style="padding-bottom: 24px;" >
            <div style="padding-bottom: 10px;margin-top: 20px">
                <span class="info__txt" style="font-size: 24px;">Ключевые навыки</span>
                <div style="width: 100%; height: 2px; background-color: #adadad;"></div>
            </div>
            <div style="width: 100%; ">
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Знание языков
                </div>
                <div style="float: left;width:500px;">
                    <div>
                      @foreach ($resume->languages as $language)
                      <div>{{$language->language}} <span class="info__txt">— {{$language->know_lang}}</span></div>
                      @endforeach
                    </div>
                </div>
                <div style="clear: both"></div><br/>
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Навыки работы с програмным обеспечением
                </div>
                <div style="float: left;width:500px;">
                    <div>{{$resume->know_softs}}</div>
                </div>
            </div>
        </section>

        <section style="padding-bottom: 24px;" >
            <div style="padding-bottom: 10px;margin-top: 20px">
                        <span class="info__txt" style="font-size: 24px;">
                            Дополнительная информация
                        </span>
                <div style="width: 100%; height: 2px; background-color: #adadad;"></div>
            </div>
            <div style="display: flex; width: 100%; padding-bottom: 24px;">
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Командировки
                </div>
                <div  style="float: left;width:500px;">{{$resume->bussinesTrip}}</div>
            </div><br/>
            <div style="display: flex; width: 100%; padding-bottom: 24px;">
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Водительское удостоверение
                </div>
                <div  style="float: left;width:500px;">{{$resume->driverLicense}}</div>
            </div><br/><br/>
            <div style="clear: both; width: 100%; padding-bottom: 24px;">
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Отметки о судимости
                </div>
                <div  style="float: left;width:500px;">{{$resume->criminalInfo}}</div>
            </div><br/>
            <div style="clear: both; width: 100%; padding-bottom: 24px;">
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Отношение к воинской обязанности
                </div>
                <div  style="float: left;width:500px;">{{$resume->militaryService}}</div>
            </div><br/><br/>
            <div style="clear: both; width: 100%; padding-bottom: 24px;">
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Почему Вы желаете работать в группе компаний АО «НАК «Казатомпром»
                </div>
                <div  style="float: left;width:500px;">{{$resume->job_in_companies}}</div>
            </div>
            <div style="clear: both; width: 100%; padding-bottom: 24px;">
                <br/>
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Дополнительно о себе
                </div>
                <div  style="float: left;width:500px;">{{$resume->about_me}}</div>
            </div><br/>
            <div style="clear: both; width: 100%; padding-bottom: 24px;">
                <div class="info__txt" style="float: left;width: 200px; margin-right: 10px;">
                    Хобби
                </div>
                <div  style="float: left;width:500px;">{{$resume->hobby}}</div>
            </div>
        </section>
      </main>
</body>
</html>

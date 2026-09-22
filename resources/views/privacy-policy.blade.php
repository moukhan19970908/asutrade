@extends('layouts.app')

@section('title', 'Политика конфиденциальности — Asu Oil Trade')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="bg-white rounded-lg shadow-md p-6 sm:p-10">

            <header class="border-b border-gray-200 pb-6 mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Политика конфиденциальности
                </h1>
                <p class="mt-2 text-gray-600">Мобильное приложение Asu Oil Trade</p>
            </header>

            {{-- Оглавление --}}
            <nav aria-label="Содержание" class="mb-10 bg-gray-50 rounded-lg p-5">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Содержание</h2>
                <ol class="grid sm:grid-cols-2 gap-x-6 gap-y-1 text-sm text-gray-700 list-decimal list-inside">
                    <li><a href="#s1" class="hover:text-yellow-600">Общие положения</a></li>
                    <li><a href="#s2" class="hover:text-yellow-600">Какие данные собираются</a></li>
                    <li><a href="#s3" class="hover:text-yellow-600">Цели и основания обработки</a></li>
                    <li><a href="#s4" class="hover:text-yellow-600">Авторизация через WhatsApp</a></li>
                    <li><a href="#s5" class="hover:text-yellow-600">Передача данных третьим лицам</a></li>
                    <li><a href="#s6" class="hover:text-yellow-600">Сроки и место хранения</a></li>
                    <li><a href="#s7" class="hover:text-yellow-600">Защита данных</a></li>
                    <li><a href="#s8" class="hover:text-yellow-600">Права Пользователя</a></li>
                    <li><a href="#s9" class="hover:text-yellow-600">Разрешения устройства, уведомления и аналитика</a></li>
                    <li><a href="#s10" class="hover:text-yellow-600">Данные несовершеннолетних</a></li>
                    <li><a href="#s11" class="hover:text-yellow-600">Изменения Политики и контакты</a></li>
                </ol>
            </nav>

            <div class="text-gray-800 leading-relaxed space-y-10">

                <section id="s1" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">1. Общие положения</h2>
                    <p class="text-gray-700 mb-4">
                        Оператором персональных данных Пользователей мобильного приложения «AsuAuto»
                        является ТОО «Asu Oil Trade».
                    </p>
                    <div class="space-y-3">
                        <p><span class="font-semibold">1.1.</span> Реквизиты оператора: ТОО «Asu Oil Trade», БИН 170240014901, юридический адрес: 130000, МАНГИСТАУСКАЯ ОБЛАСТЬ, ГОРОД АКТАУ, МКР. 29, ЗД. 228, Республика Казахстан.</p>
                        <p><span class="font-semibold">1.2.</span> Политика разработана в соответствии с Законом Республики Казахстан «О персональных данных и их защите» от 21 мая 2013 года № 94-V и иными нормативными правовыми актами Республики Казахстан.</p>
                        <p><span class="font-semibold">1.3.</span> Политика описывает, какие персональные данные собирает Компания при использовании Приложения, с какими целями они обрабатываются, кому передаются, как долго хранятся и какие права есть у Пользователя.</p>
                        <p><span class="font-semibold">1.4.</span> Использование Приложения возможно только при условии принятия настоящей Политики. Согласие на сбор и обработку персональных данных даётся Пользователем при регистрации путём проставления отметки в соответствующем поле.</p>
                        <p><span class="font-semibold">1.5.</span> Согласие является добровольным. Пользователь вправе отказаться от предоставления данных, однако в этом случае регистрация в Приложении и участие в бонусной программе невозможны.</p>
                    </div>
                </section>

                <section id="s2" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">2. Какие данные собираются</h2>
                    <p class="text-gray-700 mb-4">
                        Компания собирает минимальный набор данных, необходимый для идентификации
                        Пользователя и ведения бонусного счёта.
                    </p>

                    <div class="overflow-x-auto mb-5">
                        <table class="w-full min-w-[40rem] text-left text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200 w-1/5">Категория данных</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200">Состав</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200 w-1/4">Источник</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Идентификационные</th>
                                <td class="px-4 py-3 align-top">Номер мобильного телефона, имя (при добровольном указании)</td>
                                <td class="px-4 py-3 align-top">Предоставляются Пользователем при регистрации</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Профиль</th>
                                <td class="px-4 py-3 align-top">Дата рождения, пол, город, данные об автомобиле (марка, модель, год выпуска) — при добровольном заполнении</td>
                                <td class="px-4 py-3 align-top">Предоставляются Пользователем</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Транзакционные</th>
                                <td class="px-4 py-3 align-top">История покупок и обращений в автосервис, суммы, наименования товаров и услуг, дата и торговая точка</td>
                                <td class="px-4 py-3 align-top">Учётная система Компании</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Бонусные</th>
                                <td class="px-4 py-3 align-top">Баланс Бонусов, история начислений и списаний, уровень в программе лояльности</td>
                                <td class="px-4 py-3 align-top">Учётная система Компании</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Технические</th>
                                <td class="px-4 py-3 align-top">Модель устройства, версия операционной системы, версия Приложения, идентификатор push-уведомлений, язык интерфейса, IP-адрес</td>
                                <td class="px-4 py-3 align-top">Собираются автоматически</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Поведенческие</th>
                                <td class="px-4 py-3 align-top">Даты и время входов, разделы Приложения, открытые Пользователем</td>
                                <td class="px-4 py-3 align-top">Собираются автоматически</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-3">
                        <p><span class="font-semibold">2.1.</span> Компания не собирает биометрические данные, данные о здоровье, данные платёжных карт и банковских счетов.</p>
                        <p><span class="font-semibold">2.2.</span> Компания не запрашивает доступ к контактам, галерее, микрофону и геолокации Пользователя, за исключением случаев, прямо описанных в разделе <a href="#s9" class="text-yellow-700 underline hover:text-yellow-800">9</a>.</p>
                        <p><span class="font-semibold">2.3.</span> Пользователь вправе не указывать данные, отмеченные как добровольные; это не влияет на доступ к основной функциональности Приложения.</p>
                    </div>
                </section>

                <section id="s3" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">3. Цели и основания обработки</h2>
                    <p class="text-gray-700 mb-4">
                        Данные обрабатываются исключительно в целях, перечисленных ниже, и не используются
                        для иных целей без отдельного согласия Пользователя.
                    </p>

                    <div class="overflow-x-auto mb-5">
                        <table class="w-full min-w-[40rem] text-left text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200 w-1/3">Цель</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200">Обрабатываемые данные</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200 w-1/4">Основание</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Регистрация и авторизация в Приложении</th>
                                <td class="px-4 py-3 align-top">Номер телефона</td>
                                <td class="px-4 py-3 align-top">Согласие Пользователя</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Ведение бонусного счёта и программы лояльности</th>
                                <td class="px-4 py-3 align-top">Номер телефона, транзакционные и бонусные данные</td>
                                <td class="px-4 py-3 align-top">Согласие Пользователя; исполнение договора</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Отображение истории заказов и обращений</th>
                                <td class="px-4 py-3 align-top">Транзакционные данные</td>
                                <td class="px-4 py-3 align-top">Согласие Пользователя; исполнение договора</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Поддержка Пользователей и рассмотрение обращений</th>
                                <td class="px-4 py-3 align-top">Номер телефона, имя, транзакционные данные</td>
                                <td class="px-4 py-3 align-top">Согласие Пользователя</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Обеспечение безопасности и предотвращение злоупотреблений</th>
                                <td class="px-4 py-3 align-top">Технические и поведенческие данные</td>
                                <td class="px-4 py-3 align-top">Законный интерес Компании</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Улучшение работы Приложения и исправление ошибок</th>
                                <td class="px-4 py-3 align-top">Технические и поведенческие данные в обезличенном виде</td>
                                <td class="px-4 py-3 align-top">Согласие Пользователя</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Направление рекламных сообщений и персональных предложений</th>
                                <td class="px-4 py-3 align-top">Номер телефона, профиль, транзакционные данные</td>
                                <td class="px-4 py-3 align-top">Отдельное согласие Пользователя</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Исполнение требований законодательства</th>
                                <td class="px-4 py-3 align-top">Транзакционные данные</td>
                                <td class="px-4 py-3 align-top">Требование закона</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-3">
                        <p><span class="font-semibold">3.1.</span> Согласие на получение рекламных сообщений запрашивается отдельно от согласия на обработку персональных данных и может быть отозвано без последствий для использования Приложения.</p>
                        <p><span class="font-semibold">3.2.</span> Компания не принимает решений, порождающих юридические последствия для Пользователя, исключительно на основании автоматизированной обработки данных.</p>
                    </div>
                </section>

                <section id="s4" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">4. Авторизация через WhatsApp</h2>
                    <p class="text-gray-700 mb-4">
                        Для подтверждения номера телефона Компания передаёт этот номер провайдеру рассылки
                        сообщений и сервису WhatsApp.
                    </p>
                    <div class="space-y-3">
                        <p><span class="font-semibold">4.1.</span> При регистрации Пользователь вводит номер мобильного телефона. Компания формирует одноразовый код подтверждения и направляет его в виде сообщения в мессенджер WhatsApp на указанный номер.</p>
                        <p><span class="font-semibold">4.2.</span> Для доставки сообщения номер телефона Пользователя передаётся:</p>
                        <ul class="list-disc pl-10 space-y-1 text-gray-800">
                            <li>провайдеру сервиса рассылки сообщений GreenApi, выступающему обработчиком по поручению Компании;</li>
                            <li>компании Meta Platforms, Inc. и её аффилированным лицам, обеспечивающим работу сервиса WhatsApp Business.</li>
                        </ul>
                        <p><span class="font-semibold">4.3.</span> Передаётся только номер телефона и текст сообщения с кодом подтверждения. Иные данные Пользователя — история заказов, баланс Бонусов, данные профиля — указанным лицам не передаются.</p>
                        <p><span class="font-semibold">4.4.</span> Обработка данных в сервисе WhatsApp регулируется политикой конфиденциальности Meta Platforms, доступной по адресу <a href="https://www.whatsapp.com/legal/privacy-policy?lang=ru" target="_blank" rel="noopener noreferrer" class="text-yellow-700 underline hover:text-yellow-800 break-all">https://www.whatsapp.com/legal/privacy-policy?lang=ru</a>. Компания не контролирует обработку данных в инфраструктуре этого сервиса.</p>
                        <p><span class="font-semibold">4.5.</span> Одноразовый код действует ограниченное время и не хранится Компанией в открытом виде после подтверждения номера.</p>
                        <p><span class="font-semibold">4.6.</span> Регистрируясь в Приложении, Пользователь подтверждает согласие на передачу своего номера телефона указанным лицам в объёме, необходимом для доставки кода подтверждения.</p>
                    </div>
                </section>

                <section id="s5" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">5. Передача данных третьим лицам</h2>
                    <p class="text-gray-700 mb-4">
                        Компания не продаёт персональные данные и передаёт их только лицам, перечисленным
                        в настоящем разделе.
                    </p>

                    <div class="overflow-x-auto mb-5">
                        <table class="w-full min-w-[40rem] text-left text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200 w-1/3">Получатель</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200">Передаваемые данные</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200 w-1/4">Назначение</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Провайдер рассылки сообщений GreenApi</th>
                                <td class="px-4 py-3 align-top">Номер телефона</td>
                                <td class="px-4 py-3 align-top">Доставка кода подтверждения и сервисных сообщений</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Meta Platforms, Inc. (WhatsApp)</th>
                                <td class="px-4 py-3 align-top">Номер телефона</td>
                                <td class="px-4 py-3 align-top">Доставка сообщения в мессенджер</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Google LLC (Firebase Cloud Messaging)</th>
                                <td class="px-4 py-3 align-top">Идентификатор push-уведомлений, технические данные устройства</td>
                                <td class="px-4 py-3 align-top">Доставка push-уведомлений</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Apple Inc. (APNs)</th>
                                <td class="px-4 py-3 align-top">Идентификатор push-уведомлений</td>
                                <td class="px-4 py-3 align-top">Доставка push-уведомлений на устройства iOS</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Хостинг-провайдер ТОО «1 Клауд»</th>
                                <td class="px-4 py-3 align-top">Все данные Аккаунта</td>
                                <td class="px-4 py-3 align-top">Размещение и обслуживание серверной инфраструктуры</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Государственные органы</th>
                                <td class="px-4 py-3 align-top">Данные в объёме запроса</td>
                                <td class="px-4 py-3 align-top">Исполнение законных требований</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-3">
                        <p><span class="font-semibold">5.1.</span> Передача данных обработчикам осуществляется на основании договоров, содержащих обязательства по обеспечению конфиденциальности и безопасности данных.</p>
                        <p><span class="font-semibold">5.2.</span> Часть указанных получателей расположена за пределами Республики Казахстан. Такая трансграничная передача осуществляется с согласия Пользователя и при условии обеспечения получателем защиты персональных данных в соответствии со статьёй 16 Закона Республики Казахстан «О персональных данных и их защите».</p>
                        <p><span class="font-semibold">5.3.</span> Компания не передаёт данные Пользователей рекламным сетям и брокерам данных.</p>
                    </div>
                </section>

                <section id="s6" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">6. Сроки и место хранения</h2>
                    <p class="text-gray-700 mb-4">
                        Данные хранятся на серверах, расположенных на территории Республики Казахстан.
                    </p>
                    <div class="space-y-3">
                        <p><span class="font-semibold">6.1.</span> Компания хранит базу персональных данных граждан Республики Казахстан на территории Республики Казахстан в соответствии с требованиями законодательства.</p>
                        <p><span class="font-semibold">6.2.</span> Сроки хранения:</p>
                    </div>

                    <div class="overflow-x-auto my-5">
                        <table class="w-full text-left text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200 w-2/5">Категория данных</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 border-b border-gray-200">Срок хранения</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Данные Аккаунта и профиля</th>
                                <td class="px-4 py-3 align-top">В течение срока использования Приложения и до удаления Аккаунта</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Транзакционные данные</th>
                                <td class="px-4 py-3 align-top">5 лет — срок, установленный законодательством о бухгалтерском учёте</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Бонусные данные</th>
                                <td class="px-4 py-3 align-top">В течение срока действия Аккаунта</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Технические и поведенческие данные</th>
                                <td class="px-4 py-3 align-top">12 месяцев</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Обращения в поддержку</th>
                                <td class="px-4 py-3 align-top">3 года</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-3">
                        <p><span class="font-semibold">6.3.</span> По истечении срока хранения данные уничтожаются или обезличиваются.</p>
                        <p><span class="font-semibold">6.4.</span> Данные, подлежащие хранению в силу требований законодательства, сохраняются даже после удаления Аккаунта, но не используются для маркетинговых целей.</p>
                    </div>
                </section>

                <section id="s7" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">7. Защита данных</h2>
                    <p class="text-gray-700 mb-4">
                        Компания принимает правовые, организационные и технические меры для защиты
                        персональных данных от неправомерного доступа, изменения, распространения
                        и уничтожения.
                    </p>
                    <div class="space-y-3">
                        <p><span class="font-semibold">7.1.</span> Применяемые меры включают:</p>
                        <ul class="list-disc pl-10 space-y-1 text-gray-800">
                            <li>шифрование трафика между Приложением и серверами Компании по протоколу TLS;</li>
                            <li>ограничение доступа сотрудников к персональным данным по принципу минимально необходимых прав;</li>
                            <li>хранение токенов авторизации в защищённом хранилище устройства;</li>
                            <li>ведение журналов доступа к данным и регулярное резервное копирование;</li>
                            <li>обязательства о неразглашении для сотрудников, имеющих доступ к данным.</li>
                        </ul>
                        <p><span class="font-semibold">7.2.</span> Компания назначает лицо, ответственное за организацию обработки персональных данных, контакты которого указаны в разделе <a href="#s11" class="text-yellow-700 underline hover:text-yellow-800">11</a>.</p>
                        <p><span class="font-semibold">7.3.</span> При выявлении инцидента, повлёкшего несанкционированный доступ к персональным данным, Компания уведомляет затронутых Пользователей и уполномоченный орган в порядке, установленном законодательством.</p>
                        <p><span class="font-semibold">7.4.</span> Полная безопасность передачи данных через сети связи не может быть гарантирована. Пользователь принимает на себя риски, связанные с использованием небезопасных сетей и утратой контроля над своим устройством.</p>
                    </div>
                </section>

                <section id="s8" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">8. Права Пользователя</h2>
                    <p class="text-gray-700 mb-4">
                        Пользователь вправе в любой момент получить информацию о своих данных, изменить их,
                        отозвать согласие и удалить Аккаунт.
                    </p>
                    <div class="space-y-3">
                        <p><span class="font-semibold">8.1.</span> Пользователь имеет право:</p>
                        <ul class="list-disc pl-10 space-y-1 text-gray-800">
                            <li>получать сведения о наличии у Компании его персональных данных, а также об условиях доступа к ним;</li>
                            <li>требовать изменения или дополнения своих данных при их неполноте или неточности;</li>
                            <li>требовать блокирования или уничтожения данных, обрабатываемых с нарушением законодательства;</li>
                            <li>отозвать согласие на обработку персональных данных;</li>
                            <li>обжаловать действия Компании в уполномоченном органе по защите персональных данных или в суде.</li>
                        </ul>
                        <p><span class="font-semibold">8.2.</span> <span class="font-semibold">Удаление Аккаунта.</span> Пользователь может удалить Аккаунт самостоятельно в разделе «Настройки → Удалить аккаунт». Удаление влечёт аннулирование накопленных Бонусов.</p>
                        <p><span class="font-semibold">8.3.</span> Обращения по вопросам обработки персональных данных рассматриваются в срок, не превышающий 15 рабочих дней с даты получения, если иной срок не установлен законодательством.</p>
                        <p><span class="font-semibold">8.4.</span> Уполномоченный орган по защите персональных данных: Комитет по информационной безопасности Министерства цифрового развития, инноваций и аэрокосмической промышленности Республики Казахстан.</p>
                    </div>
                </section>

                <section id="s9" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">9. Разрешения устройства, уведомления и аналитика</h2>
                    <div class="space-y-3">
                        <p><span class="font-semibold">9.1.</span> Приложение запрашивает разрешение на отправку push-уведомлений. Разрешение является добровольным и может быть отозвано в настройках устройства в любой момент.</p>
                        <p><span class="font-semibold">9.2.</span> Push-уведомления делятся на сервисные (начисление и списание Бонусов, статус заказа) и рекламные. Отключить рекламные уведомления можно в разделе «Настройки → Уведомления» Приложения.</p>
                        <p><span class="font-semibold">9.3.</span> Приложение использует Firebase Analytics / Google Analytics для сбора обезличенной статистики использования: частота запусков, используемые разделы, сбои Приложения.</p>
                        <p><span class="font-semibold">9.4.</span> Приложение не использует файлы cookie. Для хранения состояния сессии используется локальное хранилище устройства, данные из которого удаляются при удалении Приложения.</p>
                        <p><span class="font-semibold">9.5.</span> Компания не использует технологии сквозного отслеживания Пользователей на сторонних сайтах и в сторонних приложениях.</p>
                    </div>
                </section>

                <section id="s10" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">10. Данные несовершеннолетних</h2>
                    <div class="space-y-3">
                        <p><span class="font-semibold">10.1.</span> Приложение не предназначено для лиц, не достигших 18 лет, и не адресовано детям.</p>
                        <p><span class="font-semibold">10.2.</span> Компания не осуществляет целенаправленный сбор персональных данных лиц, не достигших 14 лет.</p>
                        <p><span class="font-semibold">10.3.</span> При выявлении факта регистрации несовершеннолетнего без согласия законного представителя Компания удаляет соответствующий Аккаунт и относящиеся к нему данные.</p>
                        <p><span class="font-semibold">10.4.</span> Законный представитель вправе направить запрос об удалении данных несовершеннолетнего по контактам, указанным в разделе <a href="#s11" class="text-yellow-700 underline hover:text-yellow-800">11</a>.</p>
                    </div>
                </section>

                <section id="s11" class="scroll-mt-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">11. Изменения Политики и контакты</h2>
                    <div class="space-y-3">
                        <p><span class="font-semibold">11.1.</span> Компания вправе изменять настоящую Политику. Актуальная редакция публикуется в Приложении и по адресу <a href="https://asu-trade.com/privacy-policy" class="text-yellow-700 underline hover:text-yellow-800 break-all">https://asu-trade.com/privacy-policy</a> с указанием даты редакции.</p>
                        <p><span class="font-semibold">11.2.</span> О существенных изменениях, затрагивающих объём собираемых данных или цели обработки, Пользователи уведомляются через Приложение не менее чем за 10 календарных дней до вступления изменений в силу.</p>
                        <p><span class="font-semibold">11.3.</span> Контакты для обращений:</p>
                    </div>

                    <div class="overflow-x-auto mt-5">
                        <table class="w-full text-left border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-gray-200 w-1/3">Поле</th>
                                <th scope="col" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-gray-200">Значение</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Оператор</th>
                                <td class="px-4 py-3">ТОО «Asu Oil Trade»</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">БИН</th>
                                <td class="px-4 py-3">170240014901</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Адрес</th>
                                <td class="px-4 py-3">130000, МАНГИСТАУСКАЯ ОБЛАСТЬ, ГОРОД АКТАУ, МКР. 29, ЗД. 228</td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Электронная почта</th>
                                <td class="px-4 py-3">
                                    <a href="mailto:info@asuauto.kz" class="text-yellow-700 underline hover:text-yellow-800">info@asuauto.kz</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-700 align-top">Телефон</th>
                                <td class="px-4 py-3">
                                    <a href="tel:+77788084030" class="text-yellow-700 underline hover:text-yellow-800">+7 778 808 40 30</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', array('as' => 'get-home', 'uses' => 'HomeController@getHome'));

Route::middleware(['GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:150,15'])->group(function () {

	Route::get('/register', 'Auth\RegisterController@getRegister');
	Route::post('/register', 'Auth\RegisterController@postRegister');
	Route::get('/register/confirm-email', 'Auth\AuthController@confirmEmail');
	Route::get('/login', array('as' => 'view-login','uses' => 'Auth\LoginController@getLogin'));
	Route::post('/login', array('as' => 'login','uses' => 'Auth\LoginController@postLogin'));
    Route::get('/logout', array('as' => 'logout','uses' => 'Auth\LoginController@logout'));

    Route::get('/terms-of-use', array('as' => 'get-terms', 'uses' => 'HomeController@getTerms'));
    Route::get('/privacy-policy', array('as' => 'get-policy', 'uses' => 'HomeController@getPrivacy'));

    Route::get('/contact', array('as' => 'get-contact', 'uses' => 'HomeController@getContact'));
    Route::post('/contact', array('as' => 'post-contact', 'uses' => 'HomeController@postContact'));

    Route::get('/news', array('as' => 'get-home-news', 'uses' => 'HomeController@getNews'));
    Route::get('/news/{slug}', array('as' => 'get-home-news-view-post', 'uses' => 'HomeController@getViewNews'));

    Route::get('/events', array('as' => 'get-home-events', 'uses' => 'HomeController@getEvents'));
    Route::get('/event/{slug}', array('as' => 'get-home-event-view-post', 'uses' => 'HomeController@getViewEvent'));

    Route::get('/teachers', array('as' => 'get-home-teachers', 'uses' => 'HomeController@getTeachers'));

    Route::get('/admin', array('as' => 'get-admin','uses' => 'AdminController@getAdminDashboard'));

    Route::get('/admin/pending', array('as' => 'get-admin-pending-users','uses' => 'AdminController@getAdminPendingUsers'));
    Route::get('/admin/pending-data', array('as' => 'get-admin-pending-users-data','uses' => 'AdminController@getAdminPendingUsersData'));
    Route::post('/admin/accept/{id}/status', array('as' => 'get-admin-accept-user','uses' => 'AdminController@postAdminAcceptUser'));
    Route::post('/admin/decline/{id}/status', array('as' => 'get-admin-decline-user','uses' => 'AdminController@postAdminDeclineUser'));

    Route::get('/admin/list', array('as' => 'get-admin-list-users','uses' => 'AdminController@getAdminListUsers'));
    Route::get('/admin/userlist-data', array('as' => 'get-admin-list-user-data','uses' => 'AdminController@getAdminListUsersData'));

    Route::get('/admin/user/grade-level', array('as' => 'get-admin-list-grade-level-users','uses' => 'AdminController@getAdminUsersGradeLevel'));
    Route::get('/admin/user/grade-level-data', array('as' => 'get-admin-list-grade-level-users-data','uses' => 'AdminController@getAdminUsersGradeLevelData'));
    Route::post('/admin/user/grade-level/{id}/transfer', array('as' => 'post-change-grade-level','uses' => 'AdminController@postAdminChangeGradeLevel'));

    Route::get('/admin/security-keys', array('as' => 'get-admin-list-keys','uses' => 'AdminController@getAdminKeys'));
    Route::get('/admin/securitykeys-list-data', array('as' => 'get-admin-securitykeys-list-data','uses' => 'AdminController@getAdminKeysData'));
    Route::get('/admin/security-keys/generate', array('as' => 'get-admin-generate-keys','uses' => 'AdminController@getAdminGenerateKeys'));
    Route::post('/admin/security-keys/generate', array('as' => 'post-admin-generate-keys','uses' => 'AdminController@postAdminGenerateKeys'));

    Route::get('/admin/announcements', array('as' => 'get-admin-announcements','uses' => 'AdminController@getAdminAnnouncements'));
    Route::get('/admin/announcements-data', array('as' => 'get-admin-announcements-data','uses' => 'AdminController@getAdminAnnouncementsData'));
    Route::get('/admin/announcements/view/{id}', array('as' => 'get-admin-announcements-view','uses' => 'AdminController@getAdminViewAnnouncement'));

    Route::get('/admin/files', array('as' => 'get-admin-elearning','uses' => 'AdminController@getAdminLearningMaterial'));
    Route::get('/admin/files-data', array('as' => 'get-admin-elearning-data','uses' => 'AdminController@getAdminLearningMaterialData'));
    Route::get('/admin/file/{uuid}/download', array('as' => 'get-admin-download-material','uses' => 'AdminController@downloadLearningMaterial'));

    Route::get('/admin/settings/subject', array('as' => 'get-admin-settings-subject','uses' => 'AdminController@getAdminSubject'));
    Route::get('/admin/settings/subject-data', array('as' => 'get-admin-settings-subject-data','uses' => 'AdminController@getAdminSubjectData'));
    Route::get('/admin/settings/subject/add', array('as' => 'get-admin-settings-subject-add','uses' => 'AdminController@getAdminAddSubject'));
    Route::post('/admin/settings/subject/add', array('as' => 'post-admin-settings-subject-add','uses' => 'AdminController@postAdminAddSubject'));
    Route::get('/admin/settings/subject/edit/{id}', array('as' => 'get-admin-settings-subject-edit','uses' => 'AdminController@getAdminEditSubject'));
    Route::post('/admin/settings/subject/{id}/edit', array('as' => 'post-admin-settings-subject-edit','uses' => 'AdminController@postAdminEditSubject'));
    Route::get('/admin/settings/subject/{id}/delete', array('as' => 'get-admin-settings-subject-delete','uses' => 'AdminController@deleteSubject'));
    Route::get('/admin/settings/subject/{id}/retrieve', array('as' => 'get-admin-settings-subject-retrieve','uses' => 'AdminController@retrieveSubject'));

    Route::get('/admin/settings/grade-level', array('as' => 'get-admin-settings-grade','uses' => 'AdminController@getAdminGradeLevel'));
    Route::get('/admin/settings/grade-level-data', array('as' => 'get-admin-settings-grade-data','uses' => 'AdminController@getAdminGradeLevelData'));
    Route::get('/admin/settings/grade/add', array('as' => 'get-admin-settings-grade-add','uses' => 'AdminController@getAdminAddGradeLevel'));
    Route::post('/admin/settings/grade/add', array('as' => 'post-admin-settings-grade-add','uses' => 'AdminController@postAdminAddGradeLevel'));
    Route::get('/admin/settings/grade/edit/{id}', array('as' => 'get-admin-settings-grade-edit','uses' => 'AdminController@getAdminEditGradeLevel'));
    Route::post('/admin/settings/grade/{id}/edit', array('as' => 'post-admin-settings-grade-edit','uses' => 'AdminController@postAdminEditGradeLevel'));
    Route::get('/admin/settings/grade/{id}/delete', array('as' => 'get-admin-settings-grade-delete','uses' => 'AdminController@deleteGradeLevel'));
    Route::get('/admin/settings/grade/{id}/retrieve', array('as' => 'get-admin-settings-grade-retrieve','uses' => 'AdminController@retrieveGradeLevel'));

    Route::get('/admin/settings/department', array('as' => 'get-admin-settings-department','uses' => 'AdminController@getAdminDepartment'));
    Route::get('/admin/settings/department-data', array('as' => 'get-admin-settings-department-data','uses' => 'AdminController@getAdminDepartmentData'));
    Route::get('/admin/settings/department/add', array('as' => 'get-admin-settings-department-add','uses' => 'AdminController@getAdminAddDepartment'));
    Route::post('/admin/settings/department/add', array('as' => 'post-admin-settings-department-add','uses' => 'AdminController@postAdminAddDepartment'));
    Route::get('/admin/settings/department/edit/{id}', array('as' => 'get-admin-settings-department-edit','uses' => 'AdminController@getAdminEditDepartment'));
    Route::post('/admin/settings/department/{id}/edit', array('as' => 'post-admin-settings-department-edit','uses' => 'AdminController@postAdminEditDepartment'));
    Route::get('/admin/settings/department/{id}/delete', array('as' => 'get-admin-settings-department-delete','uses' => 'AdminController@deleteDepartment'));
    Route::get('/admin/settings/department/{id}/retrieve', array('as' => 'get-admin-settings-department-retrieve','uses' => 'AdminController@retrieveDepartment'));

    Route::get('/admin/settings/position', array('as' => 'get-admin-settings-position','uses' => 'AdminController@getAdminPosition'));
    Route::get('/admin/settings/position-data', array('as' => 'get-admin-settings-position-data','uses' => 'AdminController@getAdminPositionData'));
    Route::get('/admin/settings/position/add', array('as' => 'get-admin-settings-position-add','uses' => 'AdminController@getAdminAddPosition'));
    Route::post('/admin/settings/position/add', array('as' => 'post-admin-settings-position-add','uses' => 'AdminController@postAdminAddPosition'));
    Route::get('/admin/settings/position/edit/{id}', array('as' => 'get-admin-settings-position-edit','uses' => 'AdminController@getAdminEditPosition'));
    Route::post('/admin/settings/position/{id}/edit', array('as' => 'post-admin-settings-position-edit','uses' => 'AdminController@postAdminEditPosition'));
    Route::get('/admin/settings/position/{id}/delete', array('as' => 'get-admin-settings-position-delete','uses' => 'AdminController@deletePosition'));
    Route::get('/admin/settings/position/{id}/retrieve', array('as' => 'get-admin-settings-position-retrieve','uses' => 'AdminController@retrievePosition'));

    Route::get('/admin/news', array('as' => 'get-admin-news','uses' => 'AdminController@getAdminNews'));
    Route::get('/admin/news-data', array('as' => 'get-admin-news-data','uses' => 'AdminController@getAdminNewsData'));
    Route::get('/admin/news/create', array('as' => 'get-admin-news-create','uses' => 'AdminController@getAdminCreateNews'));
    Route::post('/admin/news/create', array('as' => 'post-admin-news-create','uses' => 'AdminController@postAdminCreateNews'));
    Route::get('/admin/news/edit/{id}', array('as' => 'get-admin-news-edit','uses' => 'AdminController@getAdminEditNews'));
    Route::post('/admin/news/{id}/edit', array('as' => 'post-admin-news-edit','uses' => 'AdminController@postAdminEditNews'));
    Route::get('/admin/news/{id}/delete', array('as' => 'get-admin-news-delete','uses' => 'AdminController@deleteNews'));
    Route::get('/admin/news/{id}/retrieve', array('as' => 'get-admin-news-retrieve','uses' => 'AdminController@retrieveNews'));

    Route::get('/admin/events', array('as' => 'get-admin-events','uses' => 'AdminController@getAdminEvents'));
    Route::get('/admin/events-data', array('as' => 'get-admin-events-data','uses' => 'AdminController@getAdminEventsData'));
    Route::get('/admin/events/create', array('as' => 'get-admin-events-create','uses' => 'AdminController@getAdminCreateEvents'));
    Route::post('/admin/events/create', array('as' => 'post-admin-events-create','uses' => 'AdminController@postAdminCreateEvents'));
    Route::get('/admin/events/edit/{id}', array('as' => 'get-admin-events-edit','uses' => 'AdminController@getAdminEditEvents'));
    Route::post('/admin/events/{id}/edit', array('as' => 'post-admin-events-edit','uses' => 'AdminController@postAdminEditEvents'));
    Route::get('/admin/events/{id}/delete', array('as' => 'get-admin-events-delete','uses' => 'AdminController@deleteEvent'));
    Route::get('/admin/events/{id}/retrieve', array('as' => 'get-admin-events-retrieve','uses' => 'AdminController@retrieveEvent'));

    Route::get('/admin/messages/public', array('as' => 'get-admin-messages-public','uses' => 'AdminController@getPublicMessages'));
    Route::get('/admin/messages/public-data', array('as' => 'get-admin-messages-public-data','uses' => 'AdminController@getPublicMessagesData'));
    Route::post('/admin/messages/public/send', array('as' => 'post-admin-message-public-send','uses' => 'AdminController@postPublicMessageSend'));
    Route::get('/admin/messages/private', array('as' => 'get-admin-messages-private','uses' => 'AdminController@getPrivateMessages'));

    Route::get('/admin/profile', array('as' => 'get-admin-profile','uses' => 'AdminController@getAdminProfile'));
    Route::post('/admin/profile', array('as' => 'post-admin-profile','uses' => 'AdminController@postgetAdminProfile'));
    Route::post('/admin/profile/password', array('as' => 'post-admin-profile-password','uses' => 'AdminController@postAdminProfilePassword'));


    Route::get('/faculty', array('as' => 'get-faculty','uses' => 'FacultyController@getFacultyDashboard'));
    Route::get('/faculty/elearning', array('as' => 'get-faculty-elearning','uses' => 'FacultyController@getFacultyLearningMaterial'));
    Route::get('/faculty/elearning-data', array('as' => 'get-faculty-elearning-data','uses' => 'FacultyController@getFacultyLearningMaterialData'));
    Route::get('/faculty/elearning/upload', array('as' => 'get-faculty-learning-material','uses' => 'FacultyController@getFacultyUploadLearningMaterial'));
    Route::post('/faculty/elearning/upload', array('as' => 'post-faculty-learning-material','uses' => 'FacultyController@postFacultyUploadLearningMaterial'));
    Route::get('/faculty/file/{uuid}/download', array('as' => 'get-faculty-download-material','uses' => 'FacultyController@downloadLearningMaterial'));
    Route::get('/faculty/file/{uuid}/delete', array('as' => 'get-faculty-delete-material','uses' => 'FacultyController@deleteLearningMaterial'));
    Route::get('/faculty/file/{uuid}/retrieve', array('as' => 'get-faculty-retrieve-material','uses' => 'FacultyController@retrieveLearningMaterial'));

    Route::get('/faculty/announcements', array('as' => 'get-faculty-announcements','uses' => 'FacultyController@getFacultyAnnouncements'));
    Route::get('/faculty/announcements-data', array('as' => 'get-faculty-announcements-data','uses' => 'FacultyController@getFacultyAnnouncementsData'));
    Route::get('/faculty/announcements/create', array('as' => 'get-faculty-announcements-create','uses' => 'FacultyController@getFacultyCreateAnnouncements'));
    Route::post('/faculty/announcements/create', array('as' => 'post-faculty-announcements-create','uses' => 'FacultyController@postFacultyCreateAnnouncements'));
    Route::get('/faculty/announcements/edit/{id}', array('as' => 'get-faculty-announcements-edit','uses' => 'FacultyController@getFacultyEditAnnouncements'));
    Route::post('/faculty/announcements/{id}/edit', array('as' => 'post-faculty-announcements-edit','uses' => 'FacultyController@postFacultyEditAnnouncements'));
    Route::get('/faculty/announcements/{id}/delete', array('as' => 'get-faculty-announcements-delete','uses' => 'FacultyController@deleteAnnouncement'));
    Route::get('/faculty/announcements/{id}/retrieve', array('as' => 'get-faculty-announcements-retrieve','uses' => 'FacultyController@retrieveAnnouncement'));
    Route::get('/faculty/announcements/view/{id}', array('as' => 'get-faculty-announcements-view','uses' => 'FacultyController@getFacultyViewAnnouncement'));

    Route::get('/faculty/class', array('as' => 'get-faculty-class','uses' => 'FacultyController@getFacultyClass'));
    Route::get('/faculty/class-data', array('as' => 'get-faculty-class-data','uses' => 'FacultyController@getFacultyClassData'));
    Route::get('/faculty/class/add', array('as' => 'get-faculty-add-class','uses' => 'FacultyController@getFacultyAddClass'));
    Route::post('/faculty/class/add', array('as' => 'post-faculty-add-class','uses' => 'FacultyController@postFacultyAddClass'));
    Route::get('/faculty/class/edit/{id}', array('as' => 'get-faculty-class-edit','uses' => 'FacultyController@getFacultyEditClass'));
    Route::post('/faculty/class/{id}/edit', array('as' => 'post-faculty-class-edit','uses' => 'FacultyController@postFacultyEditClass'));
    Route::get('/faculty/class/{id}/delete', array('as' => 'get-faculty-class-delete','uses' => 'FacultyController@deleteClass'));
    Route::get('/faculty/class/{id}/retrieve', array('as' => 'get-faculty-class-retrieve','uses' => 'FacultyController@retrieveClass'));

    Route::get('/faculty/messages/public', array('as' => 'get-faculty-messages-public','uses' => 'FacultyController@getFacultyPublicMessages'));
    Route::get('/faculty/messages/public-data', array('as' => 'get-faculty-messages-public-data','uses' => 'FacultyController@getFacultyPublicMessagesData'));
    Route::post('/faculty/messages/public/send', array('as' => 'post-faculty-message-public-send','uses' => 'FacultyController@postFacultyPublicMessageSend'));
    Route::get('/faculty/messages/private', array('as' => 'get-faculty-messages-private','uses' => 'FacultyController@getFacultyPrivateMessages'));

    Route::get('/faculty/profile', array('as' => 'get-faculty-profile','uses' => 'FacultyController@getFacultyProfile'));
    Route::post('/faculty/profile', array('as' => 'post-faculty-profile','uses' => 'FacultyController@postgetFacultyProfile'));
    Route::post('/faculty/profile/password', array('as' => 'post-faculty-profile-password','uses' => 'FacultyController@postFacultyProfilePassword'));


    Route::get('/student', array('as' => 'get-student','uses' => 'StudentController@getStudentDashboard'));
    Route::get('/student/elearning', array('as' => 'get-student-elearning','uses' => 'StudentController@getStudentLearningMaterial'));
    Route::get('/student/elearning-data', array('as' => 'get-student-elearning-data','uses' => 'StudentController@getStudentLearningMaterialData'));
    Route::get('/student/file/{uuid}/download', array('as' => 'get-student-download-material','uses' => 'StudentController@downloadLearningMaterial'));

    Route::get('/student/profile', array('as' => 'get-student-profile','uses' => 'StudentController@getStudentProfile'));
    Route::post('/student/profile', array('as' => 'post-student-profile','uses' => 'StudentController@postgetStudentProfile'));
    Route::post('/student/profile/password', array('as' => 'post-student-profile-password','uses' => 'StudentController@postStudentProfilePassword'));
    
});

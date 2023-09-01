<?php

namespace app\views;

class ErrorView extends AbstractView {
    public function exceptionPage($error_message) {
        return $this->core->getOutput()->renderTwigTemplate("error/ExceptionPage.twig", [
            "error_message" => $error_message
        ]);
    }

    public function courseErrorPage($error_message) {
        return $this->core->getOutput()->renderTwigTemplate("error/CourseErrorPage.twig", [
            "error_message" => $error_message,
            "course_url" => $this->core->buildCourseUrl(),
        ]);
    }

    public function errorPage($error_message) {
        return $this->core->getOutput()->renderTwigTemplate("error/ErrorPage.twig", [
            "error_message" => $error_message,
            "main_url" => $this->core->getConfig()->getBaseUrl()
        ]);
    }

    public function noGradeable($gradeable_id = null) {
        return $this->core->getOutput()->renderTwigTemplate("error/InvalidGradeable.twig", [
            "gradeable_id" => $gradeable_id
        ]);
    }

    public function noAccessCourse() {
        $user = $this->core->getUser();
        $user_id = $user->getId();
        $registration_section = $user->getRegistrationSection();
        $most_recent_access = $this->core->getQueries()->getMostRecentGradeableAccessForUser($user_id);
        
        return $this->core->getOutput()->renderTwigTemplate("error/NoAccessCourse.twig", [
            "course_name" => $this->core->getDisplayedCourseName(),
            "semester" => $this->core->getFullSemester(),
            "main_url" => $this->core->getConfig()->getBaseUrl(),
            "ability_to_readd" => false
        ]);
    }

    public function unbuiltGradeable($gradeable_title) {
        return $this->core->getOutput()->renderTwigTemplate('error/UnbuiltGradeable.twig', [
            'title' => $gradeable_title
        ]);
    }

    public function genericError(array $error_messages) {
        return $this->core->getOutput()->renderTwigTemplate('error/GenericError.twig', [
            'error_messages' => $error_messages
        ]);
    }
}

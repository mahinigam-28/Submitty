<?php

namespace app\views;

use app\libraries\DateUtils;

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
        return $this->core->getOutput()->renderTwigTemplate("error/NoAccessCourse.twig", [
            "course_name" => $this->core->getDisplayedCourseName(),
            "semester" => $this->core->getFullSemester(),
            "main_url" => $this->core->getConfig()->getBaseUrl(),
            "ability_to_readd" => $this->canRejoinCourse()
        ]);
    }

    /** 
     * Returns if the user is allowed to self-readd to a course after being dropped.
     * @param bool True if can readd, false otherwise.
     */
    private function canRejoinCourse() {
        $user = $this->core->getUser();

        // If manually removed from course, this was probably intentional removal.
        if ($user->isManualRegistration()) {
            return false;
        }

        $user_id = $user->getId();
        $most_recent_access = $this->core->getQueries()->getMostRecentGradeableAccessForUser($user_id);
        // If removed from course within last 3 days, can readd self.
        if (DateUtils::calculateDayDiff($most_recent_access) <= 3) {
            return true;
        }

        $term_start_date = $this->core->getQueries()->getCurrentTermStartDate();
        // If never accessed course but is within first two weeks of term, can readd self.
        if (DateUtils::calculateDayDiff($most_recent_access, $term_start_date) <= 14) {
            return true;
        }

        return false;
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

<p align="center">
  <img src="public/logo.svg" width="128" height="128" alt="LastPing Logo">
</p>

# LastPing

LastPing is a reliable, open-source dead-man's switch and automated emergency notification system built on the Laravel framework. It is designed to monitor for user activity and execute a predefined set of actions if a user fails to check in within a specified timeframe, indicating they may be incapacitated or otherwise in need of assistance.

## Project Motivation

In a digital world, ensuring that trusted contacts are alerted during an emergency is critical. LastPing provides a fail-safe mechanism for individuals who may work in high-risk environments, live alone, or wish to have an automated system in place to act on their behalf if they are unable to. By requiring periodic check-ins, the system provides peace of mind that a silent alarm will be raised when it matters most.

---

## Architecture Overview

LastPing is built as a robust, event-driven Laravel application. Its core logic is managed by a state machine that transitions a user's status through a defined lifecycle.

- **Laravel Backend:** The application leverages the power and security of the Laravel framework for its core logic, routing, and ORM.
- **Scheduler-Driven Monitoring:** A fundamental component is the Laravel Task Scheduler, which runs a command periodically (e.g., every minute) to check for users who have missed their check-in window. This cron-based approach ensures continuous, reliable monitoring.
- **State Machine:** Each user's monitoring status is represented by a clear state: `ACTIVE`, `WARNING`, `TRIGGERED`, and `PURGED`. The system transitions between these states based on check-in activity and time elapsed.
- **Event-Driven:** The system heavily utilizes Laravel's eventing system. State transitions, notifications, and data purges are dispatched as distinct events, allowing for clean, decoupled, and extensible code. Listeners handle the corresponding side effects, such as sending emails or logging actions.
- **Immutable Audit Log:** All significant actions—such as check-ins, warnings, triggers, and data purges—are recorded in an immutable audit trail to ensure full accountability and traceability.

---

## Features

- **Configurable Check-in Intervals:** Users can define their own check-in frequency (e.g., every 24 hours) and an optional grace period.
- **Automated Warning System:** If a check-in is missed, the system sends an initial warning notification directly to the user.
- **Emergency Contact Notifications:** In a triggered state, pre-configured trusted contacts are notified via email with a user-defined message.
- **Safe Data Purge:** A configurable data destruction mechanism can either soft-delete or hard-delete user data to protect privacy after the system is triggered. This is controlled via environment variables to prevent accidental data loss.
- **API & UI Check-ins:** Users can check in by calling a secure API endpoint or by interacting with a simple web interface.
- **Complete Audit Trail:** Every state change and action is logged for security and review.

---

## High-Level System Flow

The system operates on a simple yet effective lifecycle:

1.  **ACTIVE:** A user is in the `ACTIVE` state as long as they check in within their configured interval. Each successful check-in resets their "due by" timestamp.

2.  **WARNING:** If the Laravel Scheduler detects that a user has missed their check-in time, it transitions them to the `WARNING` state. An event is fired to send a warning email to the user, reminding them to check in. The grace period timer begins.

3.  **TRIGGERED:** If the user fails to check in before the grace period expires, the scheduler transitions them to the `TRIGGERED` state. This is the critical action phase.
    - An event is dispatched to notify all pre-configured emergency contacts.
    - A separate event is dispatched to handle the user's data according to the system's configuration (`PURGE_METHOD`).

4.  **PURGED:** After the data handling action is complete, the user is moved to a final `PURGED` state, and no further actions are taken. This is a terminal state.

---

## Installation and Setup

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/your-username/lastping.git
    cd lastping
    ```

2.  **Install Dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Configuration:**
    - Copy the example environment file:
      ```bash
      cp .env.example .env
      ```
    - Generate an application key:
      ```bash
      php artisan key:generate
      ```
    - Configure your database, mail server, and other environment variables in the `.env` file. Pay close attention to the `PURGE_METHOD` setting.

4.  **Run Database Migrations:**
    ```bash
    php artisan migrate
    ```

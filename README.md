# Restrict Public URL Creation (YOURLS)

Restricts URL creation to logged-in users only. Anonymous visitors (public
form or unauthenticated API calls) receive an error; logged-in admins and
authenticated API callers can create short URLs as normal.

![image](https://github.com/user-attachments/assets/9534a5f3-ee8c-4f38-9f47-f399292cbbb8)

## Install

1. Copy this folder to `YOURLS_ROOT/user/plugins/restrict-public-url-creation/`.
2. In the YOURLS admin area, go to **Manage Plugins** and activate
   **Restrict Public URL Creation**.
3. No configuration needed. Anonymous creation attempts now fail with
   `URL creation is restricted to logged-in users.` while logged-in users
   are unaffected.

## Requirements

- YOURLS 1.7 or later (uses the `add_new_link` filter and
  `yourls_is_valid_user()`).

| Plugin version | YOURLS version | Notes |
| --- | --- | --- |
| 1.0 | 1.7+ | Anonymous-only blocking: the `add_new_link` filter returns the original result for logged-in users and a failure result for anonymous requests. |

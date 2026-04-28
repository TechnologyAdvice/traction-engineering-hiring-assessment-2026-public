# Take-Home Assignment — Mission Control API

**Approximate time:** 60 minutes (including reading this README).

---

## Engineering Philosophy

This assessment reflects how our engineering team works.

We value:

### 1. Maintainability

Engineers write code for **other engineers**, not just for machines. We care about readability, clear naming, incremental improvement, leaving code better than you found it, and avoiding unnecessary complexity.

### 2. Ownership and Pragmatic Judgment

Most engineering work happens in **existing systems**, not greenfield projects. We look for engineers who can read unfamiliar code, understand requirements, make reasonable assumptions, and deliver a focused solution. We prefer **pragmatic solutions over perfect architecture**.

### 3. Responsible AI Usage

We encourage using AI tools (Cursor, ChatGPT, Copilot). We want engineers who **leverage AI effectively** but still understand the code they ship. Candidates must be able to **explain every line of code they submit**, regardless of how it was generated.

---

## What We Are Testing

- **Maintainable code** — Readable, logically structured, easy to extend; clarity over cleverness.
- **Brownfield engineering** — Understanding existing patterns, implementing changes without rewriting everything, extending functionality cleanly.
- **Responsible AI usage** — You may use AI; you must understand your code and document how AI was used.

---

## System Theme

The API represents a fictional **Space Mission Control command center**. You can create missions, launch satellites, check mission health, report satellite status, and decommission satellites. The API is **interactive** (GET, POST, PATCH, DELETE) so the end result is fun and usable.

---

## Prerequisites

- A Linux environment
- PHP 8.3+
- Composer
- Cursor or similar editor (AI-assisted work is allowed; see deliverables)

---

## Setup

1. Clone this repository.
2. From the project root:

   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   touch database/database.sqlite
   php artisan migrate --seed
   ```

3. Start the server: `php artisan serve`
4. Explore the API at `http://localhost:8000/api/` and the codebase to understand what exists.

---

## API Specification

All responses are `application/json`. Error responses use the shape `{ "error": "<message>" }`.

### Endpoint Summary

| # | Method | Path | Purpose |
|---|--------|------|---------|
| 1 | GET | /api/missions | List all missions |
| 2 | GET | /api/missions/{id} | Mission detail (status, warnings, checked_at) |
| 3 | POST | /api/missions | Create a new mission |
| 4 | GET | /api/missions/{id}/satellites | List satellites for a mission |
| 5 | POST | /api/missions/{id}/satellites | Launch a satellite |
| 6 | GET | /api/satellites/{id} | Get one satellite |
| 7 | PATCH | /api/satellites/{id} | Report / update satellite health_status |
| 8 | DELETE | /api/satellites/{id} | Decommission satellite |

---

### 1. GET /api/missions

Returns all missions.

**Response** `200 OK`

```json
{
  "missions": [
    { "id": 1, "name": "Europa Explorer", "satellite_count": 4 },
    { "id": 2, "name": "Mars One", "satellite_count": 1 }
  ]
}
```

---

### 2. GET /api/missions/{id}

Returns mission detail with status, warnings, and checked_at.

**Response** `200 OK`

```json
{
  "id": 1,
  "name": "Europa Explorer",
  "status": "active",
  "satellite_count": 4,
  "warnings": [],
  "checked_at": "2026-03-06T12:00:00Z"
}
```

**Mission status rules:**

1. If a mission has **zero satellites**, status = `"inactive"`.
2. If **any satellite is offline**, status = `"critical"`.
3. If **more than two satellites are degraded**, status = `"unstable"`.
4. Otherwise status = `"active"`.

**Warnings** (a `warnings` array; these do not affect status):

- `"low_satellite_count"` — when `satellite_count < 2`
- `"multiple_degraded_satellites"` — when number of degraded satellites > 1

**checked_at** — A timestamp (e.g. ISO 8601) for when the status was calculated.

**Response** `404 Not Found` — mission does not exist

```json
{ "error": "Mission not found" }
```

---

### 3. POST /api/missions

Create a new mission.

**Request body**

```json
{ "name": "Mars One" }
```

**Response** `201 Created`

```json
{ "id": 3, "name": "Mars One" }
```

**Response** `400 Bad Request` or `422 Unprocessable Entity` — missing or invalid name

```json
{ "error": "The name field is required." }
```

---

### 4. GET /api/missions/{id}/satellites

List satellites for a mission.

**Response** `200 OK`

```json
{
  "satellites": [
    { "id": 1, "mission_id": 1, "health_status": "healthy" },
    { "id": 2, "mission_id": 1, "health_status": "degraded" }
  ]
}
```

**Response** `404 Not Found` — mission does not exist

```json
{ "error": "Mission not found" }
```

---

### 5. POST /api/missions/{id}/satellites

Launch a satellite (add to mission).

**Request body**

```json
{ "health_status": "healthy" }
```

`health_status` is optional; if omitted, default to `"healthy"`. Allowed values: `"healthy"`, `"degraded"`, `"offline"`.

**Response** `201 Created`

```json
{ "id": 5, "mission_id": 1, "health_status": "healthy" }
```

**Response** `404 Not Found` — mission does not exist

```json
{ "error": "Mission not found" }
```

**Response** `400 Bad Request` — invalid health_status

```json
{ "error": "Invalid health status." }
```

---

### 6. GET /api/satellites/{id}

Get one satellite.

**Response** `200 OK`

```json
{
  "id": 1,
  "mission_id": 1,
  "health_status": "healthy",
  "mission": { "id": 1, "name": "Europa Explorer" }
}
```

**Response** `404 Not Found` — satellite does not exist

```json
{ "error": "Satellite not found" }
```

---

### 7. PATCH /api/satellites/{id}

Report / update satellite health_status.

**Request body**

```json
{ "health_status": "degraded" }
```

Allowed values: `"healthy"`, `"degraded"`, `"offline"`.

**Response** `200 OK`

```json
{
  "id": 1,
  "mission_id": 1,
  "health_status": "degraded",
  "mission": { "id": 1, "name": "Europa Explorer" }
}
```

**Response** `404 Not Found` — satellite does not exist

```json
{ "error": "Satellite not found" }
```

**Response** `400 Bad Request` — invalid health_status

```json
{ "error": "Invalid health status." }
```

---

### 8. DELETE /api/satellites/{id}

Decommission a satellite (remove it, or soft-delete). After decommissioning, mission status and warnings should reflect the change on the next GET /api/missions/{id}.

**Response** `200 OK`

```json
{ "decommissioned": true }
```

**Response** `404 Not Found` — satellite does not exist

```json
{ "error": "Satellite not found" }
```

---

## Your Task

Implement the API so it matches this specification. Explore the codebase to see what already exists; add or change whatever is needed so that all endpoints behave as described above.

1. **Keep the code maintainable:** extract or refactor logic where it improves clarity. Avoid overengineering.
2. **Write tests** that cover the behavior in the spec (status rules, warnings, response structure, and all endpoints).
3. **Use NOTES.md** to record questions and decisions you make along the way. If you attempt a bonus task or only scope it, note your approach in NOTES.md.

By the end, the API should be **fully interactive**: create missions, launch satellites, report health, decommission, and see mission status and warnings update accordingly.

---

## Bonus (optional)

If you have time, consider one or both of these features. Implement what makes sense for the codebase, or describe in NOTES.md how you would scope and implement them.

**Bonus 1 — Notifications when something goes wrong**  
We need mission control to notify someone when something goes wrong—for example when a satellite goes offline or a mission becomes critical.

**Bonus 2 — Archiving old or completed missions**  
We want to keep the main mission list focused on active work by archiving old or completed missions. Add support for archiving.

---

## Time

Roughly 60 minutes total, including reading this README. We value clarity and judgment over completeness.

---

## AI Usage

AI tools are allowed and encouraged. You must be able to explain your solution and disclose how AI was used.

---

## NOTES.md

Include a `NOTES.md` file with your design decisions, tradeoffs, and how you approached the problem.

---

## Rules

- Do not modify or delete **`commit.txt`**

---

## Submission

1. Push the project to a private **GitHub repository**.
2. **Add holt.johnson@technologyadvice.com as a collaborator** (Settings → Collaborators → Add people) so we can run our automated grading.
3. Preserve **commit history**.
4. Include tests and **NOTES.md** with:
   - Your approach and key design decisions
   - Tradeoffs you considered
   - What you would improve with more time
   - How you used AI tools (if applicable)
5. Share the repository link with your recruiter and let them know when you're done.

---

## Commands

| Command | Description |
|---------|-------------|
| `php artisan serve` | Start the server at http://localhost:8000 |
| `php artisan test` | Run the test suite |

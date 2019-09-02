#!/usr/bin/env bash
docker run --rm --net host --user 1000:1000 --tmpfs /run/apache2:uid=1000 -v /work/sq/sq-event-planner/storage:/app/storage:rw -v /work/sq/sq-event-planner/.env.prod:/app/.env:ro docker.bank.swissquote.ch/sq-event-planner:latest

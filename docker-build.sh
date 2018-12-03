#!/usr/bin/env bash
docker build  \
  --build-arg=http_proxy=http://172.17.0.1:3129 \
  --build-arg=https_proxy=http://172.17.0.1:3129 \
  -t docker.bank.swissquote.ch/sq-event-planner:latest \
  -f Dockerfile .

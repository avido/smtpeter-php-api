# Changes

## Pending

## 0.0.7
- Updated illuminate/collections version requirement

## 0.0.6
- Fixed missing `type` key in event resource

## 0.0.5
- Smtpeter returns wrong Content-Type header for `/templates`. Therefore response isn't decoded.\
Added check to templates endpoint to see if response was decoded, if the  response isn't decoded, try to decode.

## 0.0.4
- Bugfix, moved decoding of response body to catch errors 

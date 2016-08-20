:moneybag: PostCoin Bot :moneybag:
===================

PostCoinBot it's a bot, that allow to send "free" [postcoins](http://postcoin.site/) to your team members via reactions in Slack. 

## :gem: How to install to my team

1. Press "Add to Slack" button

   <a target="_blank" href="https://postcoin.io/auth/slack"><img alt="Add to Slack" height="40" width="139" src="https://platform.slack-edge.com/img/add_to_slack.png" srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x" /></a>

2. Visit [customize page](https://slack.com/customize/emoji) and add new custom emoji:
   
   **Emoji name:** `postcoin`

   **Emoji image:** <img src="https://raw.githubusercontent.com/Lisennk/PostCoinBot/master/public/postcoin-emoji.png" alt="" height="20px;"> <a target="_blank" href="https://raw.githubusercontent.com/Lisennk/PostCoinBot/master/public/postcoin-emoji.png">Download</a>
   
3. Yep, that's all

## :whale: How to send free postcoin

Add <img src="https://raw.githubusercontent.com/Lisennk/PostCoinBot/master/public/postcoin-emoji.png" alt="" height="20px;"> reaction to any messages and their author will recieve 1 free postcoin. 

## :musical_keyboard: Available commands

   :white_check_mark: `/postcoin setwallet` --- sets your PostCoin wallet

   :white_check_mark: `/postcoin stat` --- displays your stat

   :white_check_mark: `/postcoin thisweek` --- this week leaderboard

   :white_check_mark: `/postcoin lastweek` --- last week leaderboard

   :white_check_mark: `/postcoin help` -- get help

## :rose: Other

PostCoinBot is built on top of Laravel 5.2 and Blade as engine to generate Bot answers. It's using Slack Web and Events API. Please, if you like PostCoinBot, **star** this repository. Also feel free to create pull requests and issues. 

**Special thanks to [@LoginovIlya](https://github.com/LoginovIlya) for help with server**.

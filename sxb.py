import requests
from bs4 import BeautifulSoup
import random


def input_url(url):
    if (("https://m.shangxueba.com/ask/" in url) or ("https://www.shangxueba.com/ask/" in url)) and url[-4:]=="html":
        id = url.split("/")[-1].replace(r".html", "")
        return id
    else:
        print("error")


def get_ans(session, id):
    data = {"id": id, "action": "showZuiJia"}
    r = session.post(data=data, url="https://m.shangxueba.com/ask/ask_getzuijia.aspx")
    soup = BeautifulSoup(r.content, "html.parser")
    ans = soup.find_all("div", "replyCon")
    return ans[0]


def get_title(session, id):
    r = session.get("https://m.shangxueba.com/ask/"+id+".html")
    soup = BeautifulSoup(r.content, "html.parser")
    description = soup.find_all("h1", "ques_title")
    return description[0].get_text()


def run(url):
    id = input_url(url)
    s = requests.session()
    s.headers.update({"X-Forwarded-For": "%d.%d.%d.%d" % (
    random.randint(120, 125), random.randint(1, 200), random.randint(1, 200), random.randint(1, 200)),
                      "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36",
                      })
    title = get_title(s, id)
    ans = get_ans(session=s, id=id)
    data = {"title": title, "ans": str(ans)}
    return data


run("https://m.shangxueba.com/ask/10718119.html")


-- Ref: https://blog.berd.moe/archives/xlua-reverse-note/
-- Some snippet comes from: https://bbs.pediy.com/thread-250618.htm

local boy = {money = 200, t = 200}
function boy.goToMarket(self, someMoney)
    self.money = self.money - someMoney
    self.money = self.t
end

boy['money'] = boy['t'] + t1

boy:goToMarket(100)

t1 = nil

t3 = {}
t3 = {"foo", "bar"}

print(#t3 .. "foobar" .. 'yay')

t2 = t1 + t2
t2 = t1 - t2
t2 = t1 * t2
t2 = t1 / t2
t2 = t1 // t2
t2 = t1 % t2
t2 = t1 ^ t2
t2 = t1 & t2
t2 = t1 | t2
t2 = t1 ~ t2
t2 = t1 << t2
t2 = t1 >> t2
t2 = -t1
t2 = ~t1
t2 = t3 > t2
t2 = t3 < t2
t2 = t3 == t2
t2 = t3 >= t2
t2 = t3 <= t2
t2 = t3 ~= t2
t2 = not t2

if (t2) then t1 = 5 end

t1 = t1 and t2

local a;
function f0(a) return a end

local u, v;
function f1()
    u = 1;
    local function f2() return v end
end

a(...);
f0(t1)

for k, _ in t3 do end
for k = 1, f1(3) do end

return f0(t1)
